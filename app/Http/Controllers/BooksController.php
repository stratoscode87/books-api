<?php

namespace App\Http\Controllers;

use App\Clients\BooksClient\BooksClientInterface;
use App\Http\Requests\KeywordsSearchRequest;
use App\Http\Resources\OpenBookResource;

class BooksController extends Controller
{
    public function search(KeywordsSearchRequest $request, BooksClientInterface $booksClient)
    {
        $apiResponse = $booksClient->search($request->keywords);

        return response()->json([
            'meta' => [
                'total' => $apiResponse->numFound,
                'query' => $apiResponse->q,
            ],
            'data' => OpenBookResource::collection($apiResponse->docs),
        ]);

    }
}
