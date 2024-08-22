<?php

namespace App\Http\Controllers;

use App\Clients\BooksClient\BooksClientInterface;
use App\Http\Requests\ReviewPostRequest;

class ReviewController extends Controller
{
    public function store(ReviewPostRequest $request, BooksClientInterface $booksClient)
    {
        /*{
            "work_id": "",
             "review": "text",
            "score": 6
        }*/

        $response = $booksClient->fetchWork($request->work_id);

        return response()->json([
            'data' => $response,
        ]);
    }
}
