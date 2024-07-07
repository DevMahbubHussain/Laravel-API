<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogoutRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;

class LogoutController extends Controller
{
    use ResponseTrait;
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Authentication"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Logout successfully."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items()
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to logout",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to logout."
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Error message"
     *             )
     *         )
     *     )
     * )
     */
    public function logout(LogoutRequest $request)
    {
        try {
            $this->authRepository->logout();
            return response()->json([
                'success' => true,
                'message' => 'Logout successfully.',
                'data' => [],
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
