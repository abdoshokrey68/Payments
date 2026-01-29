<?php

namespace Modules\Product\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Product\Models\Product;

class ProductService
{
    public function __construct(
        protected ProductInterface $repository
    ) {}

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAll($perPage);
    }

    public function getById(int $id): ?Product
    {
        return $this->repository->findById($id);
    }
}
