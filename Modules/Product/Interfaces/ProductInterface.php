<?php

namespace Modules\Product\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Models\Product;

interface ProductInterface {

    public function getAll(int $perPage = 15): LengthAwarePaginator;


    public function findById(int $id): ?Product;
}
