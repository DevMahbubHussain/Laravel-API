<?php

namespace App\Repositories;

use App\Contracts\CrudContract;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductRepository implements CrudContract
{
    /**
     * Undocumented function
     *
     * @return array
     */
    public function allProduct(array $filterData): Paginator
    {
        $filter = $this->getFilterData($filterData);
        $query = Product::orderBy($filter['orderBy'], $filter['order']);
        if (!empty($filter['search'])) {
            $query->where(function ($query) use ($filter) {
                $searched = '%' . $filter['search'] . '%';
                $query->where('title', 'like', $searched)
                    ->orWhere('slug', 'like', $searched);
            });
        }
        return $query->paginate($filter['perPage']);
    }

    public function getFilterData(array $filterData): array
    {
        $defaultArgs = [
            'perPage' => 10,
            'search' => '',
            'orderBy' => 'id',
            'order' => 'desc'
        ];
        return array_merge($defaultArgs, $filterData);
    }

    // get product by id 
    public function getProductById(int $id): ?Product
    {
        $product = Product::findOrFail($id);
        if (empty($product)) {
            throw new Exception("Product does not exist.", 404);
        }

        return $product;
    }

    // product create & store 
    public function createProduct(array $data): ?Product
    {
        $data = $this->prepareForDB($data);
        return Product::create($data);
    }

    // update product 
    public function updateProduct(Product $product, array $data): Product
    {
        $data = $this->prepareForDB($data, $product);
        $product->update($data);
        return $product;
    }


    // product delete 

    /**
     * Delete a product and its associated image if exists.
     *
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function deleteProduct(Product $product): bool
    {
        // Delete associated image if it exists
        if ($product->image) {
            $imageDeleted = $this->fullDeleteImage($product->image);
            if (!$imageDeleted) {
                throw new \Exception('Failed to delete associated image');
            }
        }

        // Delete the product
        return $product->delete();
    }

    // create & store product data prepare logic. 
    public function prepareForDB(array $data, ?Product $product = null): array
    {
        // slug 
        if (empty($data['slug'])) {
            $data['slug'] = $this->createUniqueSlug($data['title']);
        }
        // image 
        if (isset($data['image'])) {
            $this->deleteImage($product->image);
            $data['image'] = $this->imageUpload($data['image'], 'products');
        }

        // user_id
        $data['user_id'] = Auth::id();

        // return data 
        return $data;
    }

    public function createUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);

        // Ensure the slug is unique
        $originalSlug = $slug;
        $count = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
    private function imageUpload($image, $folder): string
    {
        $imageName = time() . '.' . $image->extension();
        $path = $folder . '/' . $imageName;
        $image->storePubliclyAs('public/' . $folder, $imageName);
        return $path;
    }

    private function deleteImage($imagePath): void
    {
        if ($imagePath && Storage::exists('public/' . $imagePath)) {
            Storage::delete('public/' . $imagePath);
        }
    }

    // private function fullDeleteImage(string $imagePath): void
    // {
    //     if (Storage::exists('public/' . $imagePath)) {
    //         Storage::delete('public/' . $imagePath);
    //     }
    // }

    private function fullDeleteImage($imageName): bool
    {
        // Example: Delete image from storage
        try {
            Storage::disk('public')->delete($imageName);
            return true; // Deletion successful
        } catch (\Exception $e) {
            // Log or handle the exception
            return false; // Deletion failed
        }
    }
}
