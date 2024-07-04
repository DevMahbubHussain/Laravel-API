<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\Paginator;

interface CrudContract
{

    public function allProduct(int $perPage): Paginator;
}
