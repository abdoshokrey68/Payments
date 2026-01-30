<?php

namespace Modules\Order\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Interfaces\OrderInterface;
use Modules\Order\Models\Order;

class OrderRepository implements OrderInterface
{
    public function __construct(
        protected Order $model
    ) {}

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with('items.product', 'payment')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ?Order
    {
        return $this->model->newQuery()
            ->with('items.product', 'payment')
            ->find($id);
    }

    public function create(array $data): Order
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);

        return $order->fresh('items.product', 'payment');
    }

    public function delete(Order $order): bool
    {
        $order->items()->delete();

        return $order->delete();
    }

    public function setStatus(Order $order, OrderStatusEnum $status): Order
    {
        $order->update(['status' => $status]);

        return $order->fresh('items.product', 'payment');
    }

    public function getOrderByUserId(int $userId, int $order_id): ?Order
    {
        return $this->model->newQuery()
            ->where('user_id', $userId)
            ->find($order_id);
    }
}
