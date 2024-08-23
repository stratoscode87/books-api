<?php

namespace App\Clients\BooksClient\OpenLibraryClient\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class WorkNotFoundException extends Exception
{
    public function __construct(public $message = 'Ops! Something went wrong', public $code = 422) {}

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }
}
