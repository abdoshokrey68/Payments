<?php

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Interfaces\ProductInterface;
use Modules\Product\Models\Product;

class ProductRepository implements ProductInterface
{
    public function __construct(
        protected Product $model
    ) {}

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->newQuery()->find($id);
    }
}
