<?php

namespace Modules\Order\Interfaces;

use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

interface OrderItemInterface
{
    /**
     * Create multiple order items for an order.
     *
     * @param  array<int, array{product_id: int, quantity: int, price: float}>  $items
     * @return array<OrderItem>
     */
    public function create(Order $order, array $items): array;

    public function update(Order $order, array $items): void;
}
