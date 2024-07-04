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
        return response()->json([
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
    public function errorResponse(string $message = "Something went wrong.", int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }
}
