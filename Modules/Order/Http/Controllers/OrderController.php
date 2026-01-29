<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\ErrorResponseEnum;
use Illuminate\Http\Request;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\OrderResource;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $service
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $orders = $this->service->getAll($perPage);

        return ApiResponse::success(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

    public function show(int $id)
    {
        $result = $this->service->getById($id);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(new OrderResource($result), 'Order retrieved successfully');
    }

    public function store(StoreOrderRequest $request)
    {
        $result = $this->service->store($request);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(new OrderResource($result), 'Order created successfully', 201);
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $result = $this->service->update($request, $id);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(new OrderResource($result), 'Order updated successfully');
    }

    public function confirm(int $id)
    {
        $result = $this->service->confirm($id);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(new OrderResource($result), 'Order confirmed successfully');
    }

    public function cancel(int $id)
    {
        $result = $this->service->cancel($id);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(new OrderResource($result), 'Order cancelled successfully');
    }

    public function destroy(int $id)
    {
        $result = $this->service->destroy($id);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error($result->message(), $result->statusCode() ?? 400);
        }

        return ApiResponse::success(null, 'Order deleted successfully');
    }
}
