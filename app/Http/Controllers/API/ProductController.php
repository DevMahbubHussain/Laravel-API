<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseTrait;


    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            /** @var \Illuminate\Contracts\Pagination\Paginator $products  */
            $products = $this->productRepository->allProduct(5); // Paginator instance
            $data = $products->items(); // Retrieve items as array
            return $this->successResponse($data, 200, 'Product Fetch Successfully');
        } catch (Exception $e) {

            return $this->errorResponse('failed to fetch data', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
