<?php

namespace App\Services\ReviewService\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ReviewNotFoundException extends Exception
{
    public function __construct(public $message = 'Review not found', public $code = 422) {}

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->message,
        ], $this->code);
    }
}
