<?php

namespace App\Clients\BooksClient\OpenLibraryClient\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class OpenLibraryNotReachableException extends Exception
{
    public function __construct(public $message = 'Ops! Something went wrong', public $code = 500) {}

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 500);
    }
}
