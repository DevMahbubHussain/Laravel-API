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
