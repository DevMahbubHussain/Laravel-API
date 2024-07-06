<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function update(ProfileRequest $profileRequest): JsonResponse
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
        $data = $profileRequest->validated();

        $updateUser = $this->userRepository->updateProfile($user, $data);
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $updateUser,
        ]);
    }
}
