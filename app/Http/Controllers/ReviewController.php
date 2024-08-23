<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewPostRequest;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function store(ReviewPostRequest $request, ReviewService $service)
    {
        $service->postReview($request->validated());

        return response()->json([
            'status' => 'processing...',
        ], 202);
    }
}
