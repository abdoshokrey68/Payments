<?php

namespace Modules\Payment\Http\Controllers\Api;

use App\ErrorResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Payment\Http\Requests\PayPaymentRequest;
use Modules\Payment\Services\PaymentService;
use Modules\Payment\Transformers\PaymentResource;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $service
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $payments = $this->service->getAll($perPage, $request->user());

        return ApiResponse::success(
            PaymentResource::collection($payments),
            'Payments retrieved successfully'
        );
    }

    public function pay(PayPaymentRequest $request)
    {
        $result = $this->service->pay($request);

        if ($result instanceof ErrorResponseEnum) {
            return ApiResponse::error(
                $result->message(),
                $result->statusCode() ?? 400
            );
        }

        return ApiResponse::success(
            new PaymentResource($result),
            'Payment successfully',
            201
        );
    }
}
