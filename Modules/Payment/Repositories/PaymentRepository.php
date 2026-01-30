<?php

namespace Modules\Payment\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Payment\Interfaces\PaymentInterface;
use Modules\Payment\Models\Payment;

class PaymentRepository implements PaymentInterface
{
    public function __construct(
        protected Payment $model
    ) {}

    public function getAllByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->whereHas('order', fn ($q) => $q->where('user_id', $userId))
            ->with('order')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Payment
    {
        return $this->model->newQuery()
            ->with('order')
            ->find($id);
    }

    public function create(array $data): Payment
    {
        return $this->model->newQuery()->create($data);
    }

    public function getPaymentByOrderId(int $orderId): ?Payment
    {
        return $this->model->newQuery()
            ->where('order_id', $orderId)
            ->first();
    }
}
