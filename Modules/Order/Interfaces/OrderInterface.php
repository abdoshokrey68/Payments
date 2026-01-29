<?php

namespace Modules\Order\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Models\Order;

interface OrderInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Order;

    public function create(array $data): Order;

    public function update(Order $order, array $data): Order;

    public function delete(Order $order): bool;

    public function setStatus(Order $order, OrderStatusEnum $status): Order;
}
