<?php

namespace App\Repositories;

use App\Contracts\CrudContract;
use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;

class ProductRepository implements CrudContract
{
    /**
     * Undocumented function
     *
     * @return array
     */
    public function allProduct(int $perPage = 10): Paginator
    {
        return Product::paginate($perPage);
    }
}
