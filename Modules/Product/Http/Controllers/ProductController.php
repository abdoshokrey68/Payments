<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Product\Services\ProductService;
use Modules\Product\Transformers\ProductResource;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $products = $this->productService->getAll($perPage);

        return ApiResponse::success(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function show(int $id)
    {
        $product = $this->productService->getById($id);

        if (! $product) {
            return ApiResponse::notFound('Product not found');
        }

        return ApiResponse::success(new ProductResource($product), 'Product retrieved successfully');
    }
}
