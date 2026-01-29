<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Product::updateOrCreate([
                'id' => $i + 1,
            ], [
                'name' => 'Product ' . $i,
                'price' => rand(100, 1000),
            ]);
        }
    }
}
