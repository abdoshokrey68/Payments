<?php

namespace Modules\Order\Services;

use App\ErrorResponseEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Interfaces\OrderItemInterface;
use Modules\Order\Interfaces\OrderInterface;
use Modules\Order\Models\Order;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Product\Models\Product;

class OrderService
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected OrderItemInterface $orderItemRepository,
        protected ProductInterface $productRepository
    ) {}

    public function getAll(int $perPage): LengthAwarePaginator
    {
        return $this->orderRepository->getAll($perPage);
    }

    public function getById(int $id): Order | ErrorResponseEnum
    {
        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        return $order;
    }

    public function store(StoreOrderRequest $request): Order | ErrorResponseEnum
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = OrderStatusEnum::PENDING;

        $productIds = array_column($data['items'], 'product_id');
        $products = $this->productRepository->findByIds($productIds);

        [$orderItemsWithPrice, $totalAmount] = $this->buildOrderItemsFromProducts($data['items'], $products);

        if (empty($orderItemsWithPrice)) {
            return ErrorResponseEnum::INVALID_PARAMETER;
        }

        $data['total_amount'] = round($totalAmount, 2);
        $order = $this->orderRepository->create($data);
        $this->orderItemRepository->create($order, $orderItemsWithPrice);

        return $order->load('items.product');
    }

    private function buildOrderItemsFromProducts(array $items, $products): array
    {
        $orderItemsWithPrice = [];
        $totalAmount = 0.0;

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                continue;
            }
            $price = (float) $product->price;
            $quantity = (int) $item['quantity'];
            $totalAmount += $price * $quantity;

            $orderItemsWithPrice[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'snapshoot' => json_encode(['name' => $product->name, 'price' => $price]),
            ];
        }

        return [$orderItemsWithPrice, $totalAmount];
    }

    public function update(UpdateOrderRequest $request, int $id): Order | ErrorResponseEnum
    {
        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        if ($order->user_id !== auth()->id()) {
            return ErrorResponseEnum::UNAUTHORIZED;
        }
        if ($order->status !== OrderStatusEnum::PENDING) {
            return ErrorResponseEnum::NOT_ACCEPTABLE;
        }

        $data = $request->validated();

        if (isset($data['items'])) {
            $productIds = array_column($data['items'], 'product_id');
            $products = $this->productRepository->findByIds($productIds);

            [$orderItemsWithPrice, $totalAmount] = $this->buildOrderItemsFromProducts($data['items'], $products);

            if (empty($orderItemsWithPrice)) {
                return ErrorResponseEnum::INVALID_PARAMETER;
            }

            $data['total_amount'] = round($totalAmount, 2);
            $this->orderItemRepository->update($order, $orderItemsWithPrice);
            unset($data['items']);
        }

        if (! empty($data)) {
            $this->orderRepository->update($order, $data);
        }

        return $order->fresh('items.product');
    }

    public function confirm(int $id): Order | ErrorResponseEnum
    {
        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        if ($order->user_id !== auth()->id()) {
            return ErrorResponseEnum::UNAUTHORIZED;
        }
        if ($order->status !== OrderStatusEnum::PENDING) {
            return ErrorResponseEnum::NOT_ACCEPTABLE;
        }

        return $this->orderRepository->setStatus($order, OrderStatusEnum::CONFIRMED);
    }

    public function cancel(int $id): Order | ErrorResponseEnum
    {
        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        if ($order->user_id !== auth()->id()) {
            return ErrorResponseEnum::UNAUTHORIZED;
        }

        return $this->orderRepository->setStatus($order, OrderStatusEnum::CANCELLED);
    }

    public function destroy(int $id): bool | ErrorResponseEnum
    {
        $order = $this->orderRepository->findById($id);

        if (! $order) {
            return ErrorResponseEnum::NOT_FOUND;
        }

        if ($order->user_id !== auth()->id()) {
            return ErrorResponseEnum::UNAUTHORIZED;
        }

        $this->orderRepository->delete($order);

        return true;
    }
}
