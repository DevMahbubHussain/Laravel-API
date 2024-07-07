<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
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
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get all products for REST API",
     *     description="Multiple status values can be provided with comma separated string",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Per page count",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="",
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Order By column name",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="id",
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order ordering - asc or desc",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="desc",
     *             type="string",
     *         )
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            /** @var \Illuminate\Contracts\Pagination\Paginator $products  */
            $products = $this->productRepository->allProduct(request()->all()); // Paginator instance
            $data = $products->items(); // Retrieve items as array
            return $this->successResponse($data, 200, 'Product Fetch Successfully');
        } catch (Exception $e) {

            return $this->errorResponse('failed to fetch data', 500);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create product",
     *     description="Create product",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Product objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product title",
     *                     type="string",
     *                     example="Product title"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product slug",
     *                     type="string",
     *                     example="product-title"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Product price",
     *                     type="integer",
     *                     example="200"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product image",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 required={"title", "price"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(ProductCreateRequest $productCreateRequest)
    {
        try {
            /** @var null|\App\Models\Product $product */
            $product = $this->productRepository->createProduct($productCreateRequest->validated());
            return $this->successResponse($product ? $product->toArray() : [], 201, 'Product Created Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('failed to create product', 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get product detail",
     *     description="Get product detail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $products = $this->productRepository->getProductById($id);
            return $this->successResponse($products ? $products->toArray() : [], 200, 'Product Fetch Successfully');
        } catch (Exception $exception) {

            return $this->errorResponse('failed to fetch product', 500);
        }
    }
    /**
     * @OA\POST(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update product",
     *     description="Update product",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="_method",
     *         in="query",
     *         description="request method",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="PUT"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Product objects",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="Product id",
     *                     type="integer",
     *                     example="Product id"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     description="Product title",
     *                     type="string",
     *                     example="Product title"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     description="Product slug",
     *                     type="string",
     *                     example="product-title"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Product price",
     *                     type="integer",
     *                     example="200"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="Product image",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 required={"id","title", "price", "slug"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        try {
            $product = $this->productRepository->updateProduct($product, $request->validated());
            return $this->successResponse($product->toArray(), 200, 'Product Updated Successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update product', 500);
        }
    }


    /**
     * @OA\DELETE(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete product",
     *     description="Delete product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="product id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $deletedProduct = $product->toArray();
            $isDeleted = $this->productRepository->deleteProduct($product);
            if ($isDeleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully',
                    'data' => $deletedProduct,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete product',
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function destroy(Product $product): JsonResponse
    // {
    //     try {
    //         $deletedProduct = $product->toArray();
    //         $this->productRepository->deleteProduct($product);
    //         return $this->successResponse($deletedProduct, 200, 'Product deleted successfully');
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Failed to delete product', 500);
    //     }
    // }
}
