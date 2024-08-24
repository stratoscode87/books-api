<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuth\LoginRequest;
use App\Http\Requests\UserAuth\RegisterRequest;
use App\Services\UserAuthService\Exceptions\InvalidCredentialsException;
use App\Services\UserAuthService\UserAuthService;

class UserAuthController extends Controller
{
    public function __construct(private UserAuthService $service) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'message' => 'User successfully registered',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(LoginRequest $request)
    {
        $apiToken = $this->service->createToken($request->validated());

        return response()->json([
            'Bearer-token' => $apiToken,
        ]);
    }

    public function logout()
    {
        $this->service->logout();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
