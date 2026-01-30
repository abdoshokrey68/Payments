<?php

namespace Modules\Order\Tests\Unit;

use App\ErrorResponseEnum;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Product\Models\Product;
use Modules\Order\Interfaces\OrderInterface;
use Modules\Order\Interfaces\OrderItemInterface;
use Modules\Order\Models\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Services\OrderService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

class OrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->initializeDependencies();
    }

    public function initializeDependencies(): array {
        $orderRepository = $this->createMock(OrderInterface::class);
        $orderItemRepository = $this->createMock(OrderItemInterface::class);
        $productRepository = $this->createMock(ProductInterface::class);
        return [
            $orderRepository,
            $orderItemRepository,
            $productRepository
        ];
    }

    public function test_get_all_orders_with_pagination(): void
    {
        $paginator = $this->createMock(LengthAwarePaginator::class);
        [$orderRepository, $orderItemRepository, $productRepository] = $this->initializeDependencies();
        $orderRepository->method('getAll')->with(15)->willReturn($paginator);

        $service = new OrderService($orderRepository, $orderItemRepository, $productRepository);
        $result = $service->getAll(15);

        $this->assertSame($paginator, $result);
    }

    public function test_get_order_by_id(): void
    {
        $order = new Order(['id' => 1, 'user_id' => 1, 'status' => 'pending', 'total_amount' => 100]);

        [$orderRepository, $orderItemRepository, $productRepository] = $this->initializeDependencies();

        $orderRepository->method('findById')->with(1)->willReturn($order);

        $service = new OrderService($orderRepository, $orderItemRepository, $productRepository);
        $result = $service->getById(1);

        $this->assertSame($order, $result);
    }
}
