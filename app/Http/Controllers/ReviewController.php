<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\ReviewGetRequest;
use App\Http\Requests\Review\ReviewPostRequest;
use App\Http\Requests\Review\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(public ReviewService $service) {}

    /*
     * Get review by ID
     */
    public function review(ReviewGetRequest $request): JsonResponse
    {
        $review = $this->service->getReview($request->id);

        return $this->service->getReviewResponse($review);
    }

    public function store(ReviewPostRequest $request): JsonResponse
    {
        $this->service->postReview($request->validated());

        return response()->json([
            'status' => 'processing...',
        ], 202);
    }

    public function put(ReviewRequest $request)
    {
        $review = $this->service->putRequest($request->validated());

        return response()->json([
            'data' => $review,
        ], 202);
    }
}
