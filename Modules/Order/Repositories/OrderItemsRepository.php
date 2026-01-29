<?php

namespace Modules\Order\Repositories;

use Modules\Order\Interfaces\OrderItemInterface;
use Modules\Order\Models\Order;

class OrderItemsRepository implements OrderItemInterface
{
    public function create(Order $order, array $items): array
    {
        $created = [];

        foreach ($items as $item) {
            $created[] = $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'snapshoot' => $item['snapshoot'] ?? null,
            ]);
        }

        return $created;
    }

    public function update(Order $order, array $items): void
    {
        $order->items()->delete();

        $this->create($order, $items);
    }
}
