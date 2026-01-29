<?php

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

    public function findByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return collect();
        }

        return $this->model->newQuery()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }
}
