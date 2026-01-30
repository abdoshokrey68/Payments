<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Product\Models\Product;
use Modules\Product\Services\ProductService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{

    public function test_get_all_products_with_pagination(): void
    {
        $paginator = $this->createMock(LengthAwarePaginator::class);
        $repository = $this->createMock(ProductInterface::class);
        $repository->method('getAll')->with(15)->willReturn($paginator);

        $service = new ProductService($repository);
        $products = $service->getAll(15);

        $this->assertSame($paginator, $products);
    }

    public function test_get_product_by_id(): void
    {
        $product = new Product(['id' => 1, 'name' => 'Test', 'price' => 100]);
        $repository = $this->createMock(ProductInterface::class);
        $repository->method('findById')->with(1)->willReturn($product);

        $service = new ProductService($repository);
        $product = $service->getById(1);

        $this->assertSame($product, $product);
    }
}
