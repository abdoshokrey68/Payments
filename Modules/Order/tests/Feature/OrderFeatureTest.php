<?php

namespace Modules\Order\Tests\Feature;

use App\Models\User;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Tests\TestCase;

class OrderFeatureTest extends TestCase
{
    public function test_store_new_order(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $product = Product::create(['name' => 'Test', 'price' => 100]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/orders', [
                'items' => [
                    ['product_id' => $product->id, 'quantity' => 2],
                ],
            ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.status', OrderStatusEnum::PENDING->value);
        $response->assertJsonPath('data.user_id', $user->id);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => OrderStatusEnum::PENDING->value,
        ]);
    }

    public function test_confirm_order(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatusEnum::PENDING,
            'total_amount' => 100,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/orders/confirm/{$order->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', OrderStatusEnum::CONFIRMED->value);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatusEnum::CONFIRMED->value,
        ]);
    }

    public function test_cancel_order(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatusEnum::PENDING,
            'total_amount' => 100,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/orders/cancel/{$order->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.status', OrderStatusEnum::CANCELLED->value);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatusEnum::CANCELLED->value,
        ]);
    }

    public function test_destroy_order(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $order = Order::create([
            'user_id' => $user->id,
            'status' => OrderStatusEnum::PENDING,
            'total_amount' => 100,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
