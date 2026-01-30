<?php

namespace Modules\Payment\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Models\Order;
use Modules\Payment\Enums\PaymentStatusEnum;
use Modules\Payment\Enums\PaymentMethodsEnum;
use Modules\Payment\Models\Payment;

class PaymentFeatureTest extends TestCase
{

    public function createInitialData($orderStatus = OrderStatusEnum::PENDING, $paymentStatus = PaymentStatusEnum::PENDING): array
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => $orderStatus,
            'total_amount' => 100,
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => $paymentStatus,
            'payment_method' => PaymentMethodsEnum::CREDIT_CARD,
            'amount' => $order->total_amount,
        ]);

        return [$user, $order];
    }

    public function test_get_all_payments_for_user(): void
    {
        [$user, $order] = $this->createInitialData(OrderStatusEnum::PENDING, PaymentStatusEnum::PENDING);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/payments');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    public function test_success_pay_payment(): void
    {
        [$user, $order] = $this->createInitialData(OrderStatusEnum::CONFIRMED);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', ['order_id' => $order->id, 'payment_method' => PaymentMethodsEnum::CREDIT_CARD]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $this->assertEquals(PaymentStatusEnum::SUCCESSFUL->value, $response->json('data.status.value'));
    }

    public function test_error_pay_payment_order_not_found(): void
    {
        [$user, $order] = $this->createInitialData();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', ['order_id' => 999, 'payment_method' => PaymentMethodsEnum::CREDIT_CARD]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.order_id', ['The selected order id is invalid.']);
    }

    public function test_error_pay_payment_order_not_confirmed(): void
    {
        [$user, $order] = $this->createInitialData(OrderStatusEnum::PENDING);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', ['order_id' => $order->id, 'payment_method' => PaymentMethodsEnum::CREDIT_CARD]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('message', 'Order not confirmed.');
    }

    public function test_error_pay_payment_order_already_paid(): void
    {
        [$user, $order] = $this->createInitialData(OrderStatusEnum::CONFIRMED, PaymentStatusEnum::SUCCESSFUL);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', ['order_id' => $order->id, 'payment_method' => PaymentMethodsEnum::CREDIT_CARD]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('message', 'Already Paid.');
    }

    public function test_error_pay_payment_payment_method_not_found(): void
    {
        [$user, $order] = $this->createInitialData(OrderStatusEnum::CONFIRMED);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', ['order_id' => $order->id, 'payment_method' => 999]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.payment_method', ['The selected payment method is invalid.']);
    }
}
