<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewGetRequest;
use App\Http\Requests\ReviewPostRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /*
     * Get review by ID
     */
    public function review(ReviewGetRequest $request, ReviewService $service): JsonResponse
    {
        $review = $service->getReview($request->id);

        return $service->getReviewResponse($review);
    }

    public function store(ReviewPostRequest $request, ReviewService $service): JsonResponse
    {
        $service->postReview($request->validated());

        return response()->json([
            'status' => 'processing...',
        ], 202);
    }
}
