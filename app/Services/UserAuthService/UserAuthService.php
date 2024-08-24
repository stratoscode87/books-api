<?php

namespace App\Services\UserAuthService;

use App\Models\User;
use App\Services\UserAuthService\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Hash;

class UserAuthService
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function createToken(array $data): string
    {
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new InvalidCredentialsException;
        }

        return $user->createToken($user->email.'-AuthToken')->plainTextToken;
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
    }
}
