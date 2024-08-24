<?php

namespace App\Services\UserAuthService\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InvalidCredentialsException extends Exception
{
    public function __construct(public $message = 'Invalid credentials', public $code = 401) {}

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->message,
        ], $this->code);
    }
}
