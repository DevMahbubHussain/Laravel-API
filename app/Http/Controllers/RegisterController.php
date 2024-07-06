<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ResponseTrait;
    private AuthRepository $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
    public function register(RegisterRequest $registerRequest)
    {

        try {
            $data = $this->authRepository->register($registerRequest->validated());
            return $this->successResponse($data, 201, 'User registered successfully.');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
