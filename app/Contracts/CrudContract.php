<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

interface CrudContract
{

    public function allProduct(array $filterData): Paginator;
    public function getProductById(int $id): object|null;

    public function createProduct(array $data): object|null;

    public function updateProduct(Product $product, array $data): Product;
    public function deleteProduct(Product $product): bool;
}
