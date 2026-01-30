<?php

namespace Modules\Payment\Services;

use App\ErrorResponseEnum;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Interfaces\OrderInterface;
use Modules\Payment\Enums\PaymentStatusEnum;
use Modules\Payment\Http\Requests\PayPaymentRequest;
use Modules\Payment\Interfaces\PaymentInterface;
use Modules\Payment\Models\Payment;
use Modules\Payment\Services\PaymentGatewayFactory;

class PaymentService
{
    public function __construct(
        protected PaymentInterface $paymentRepository,
        protected OrderInterface $orderRepository,
        protected PaymentGatewayFactory $gatewayFactory
    ) {}

    public function getAll(int $perPage, User $user): LengthAwarePaginator
    {
        return $this->paymentRepository->getAllByUser($user->id, $perPage);
    }

    public function pay(PayPaymentRequest $request): Payment | ErrorResponseEnum
    {
        $data = $request->validated();
        $order = $this->orderRepository->getOrderByUserId($request->user()->id, (int) $data['order_id']);

        if (!$order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        // dd($order->status, OrderStatusEnum::CONFIRMED);
        if ($order->status->value !== OrderStatusEnum::CONFIRMED->value) {
            return ErrorResponseEnum::ORDER_NOT_CONFIRMED;
        }

        $payment = $this->paymentRepository->getPaymentByOrderId($order->id);

        if ($payment && $payment->status->value === PaymentStatusEnum::SUCCESSFUL->value) {
            return ErrorResponseEnum::ALREADY_PAID;
        }
        // dd($payment->status->value);

        try {
            $gateway = $this->gatewayFactory->getGateway((int) $data['payment_method']);
            $success = $gateway->process($order->id, (float) $order->total_amount);

            $paymentData = [
                'order_id' => $order->id,
                'status' => $success ? PaymentStatusEnum::SUCCESSFUL : PaymentStatusEnum::FAILED,
                'payment_method' => $data['payment_method'],
                'amount' => $order->total_amount,
            ];

            $payment = $this->paymentRepository->create($paymentData);
        } catch (\Exception $e) {
            return ErrorResponseEnum::PAYMENT_FAILED;
        }

        return $payment->load('order');
    }
}
