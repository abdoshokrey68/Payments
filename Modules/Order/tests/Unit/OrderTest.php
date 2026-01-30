<?php

namespace Modules\Order\Tests\Unit;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Payment\Interfaces\PaymentInterface;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Order\Interfaces\OrderInterface;
use Modules\Order\Interfaces\OrderItemInterface;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use PHPUnit\Framework\TestCase;

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
        $paymentRepository = $this->createMock(PaymentInterface::class);
        return [
            $orderRepository,
            $orderItemRepository,
            $productRepository,
            $paymentRepository
        ];
    }

    public function test_get_all_orders_with_pagination(): void
    {
        $paginator = $this->createMock(LengthAwarePaginator::class);
        [$orderRepository, $orderItemRepository, $productRepository, $paymentRepository] = $this->initializeDependencies();
        $orderRepository->method('getAll')->with(15)->willReturn($paginator);

        $service = new OrderService($orderRepository, $orderItemRepository, $productRepository, $paymentRepository);
        $result = $service->getAll(15);

        $this->assertSame($paginator, $result);
    }

    public function test_get_order_by_id(): void
    {
        $order = new Order(['id' => 1, 'user_id' => 1, 'status' => 'pending', 'total_amount' => 100]);

        [$orderRepository, $orderItemRepository, $productRepository, $paymentRepository] = $this->initializeDependencies();

        $orderRepository->method('findById')->with(1)->willReturn($order);

        $service = new OrderService($orderRepository, $orderItemRepository, $productRepository, $paymentRepository);
        $result = $service->getById(1);

        $this->assertSame($order, $result);
    }
}
