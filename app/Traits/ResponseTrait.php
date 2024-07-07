<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{

    // success response function 
    /**
     * Return a successful JSON response.
     *
     * @param array $data
     * @param int $status
     * @param string $message
     * @return JsonResponse
     */
    public function successResponse(array $data = [], int $status = 200, string $message = "Success"): JsonResponse
    {
        if ($data instanceof \App\Models\Product) {
            $data = $data->toArray();
        }
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    // error response function 
    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public function errorResponse(string $message = "Something went wrong.", int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
