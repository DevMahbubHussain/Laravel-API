<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthRepository
{


    // Check the request if the valid user email
    public function login(array $data): array
    {
        $user = $this->getUserByEmail($data['email']);
        if (!$user) {
            throw new Exception("Sorry, user does not exist.", 404);
        }

        if (!$this->checkPassword($user, $data['password'])) {
            throw new Exception("Sorry, password does not match.", 401);
        }
        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
    }

    // register method 

    public function register(array $data): array
    {
        $user = User::create($this->prepareDataForRegister($data));
        if (!$user) {
            throw new Exception("Sorry, user does not registered, Please try again.", 500);
        }
        $tokenInstance = $this->createAuthToken($user);
        return $this->getAuthData($user, $tokenInstance);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function checkPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }
    public function createAuthToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }

    public function getAuthData(User $user, PersonalAccessTokenResult $tokenInstance): array
    {
        return [
            'user' => $user,
            'access_token' => $tokenInstance->accessToken,
            'token_type' => 'Bearer',
            'expires_at'   => Carbon::parse($tokenInstance->token->expires_at)->toDateTimeString()
        ];
    }

    public function prepareDataForRegister(array $data): array
    {
        return [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ];
    }

    // logout
    public function logout(): void
    {
        Auth::user()->tokens->each(function (
            $token,
            $key
        ) {
            $token->delete();
        });
    }


    // profile 
}
