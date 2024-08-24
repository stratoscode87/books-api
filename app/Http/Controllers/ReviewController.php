<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\ReviewGetRequest;
use App\Http\Requests\Review\ReviewPostRequest;
use App\Http\Requests\Review\ReviewRequest;
use App\Http\Resources\ReviewResource;
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
        $this->service->post($request->validated());

        return response()->json([
            'status' => 'processing...',
        ], 202);
    }

    public function update(ReviewRequest $request)
    {
        $review = $this->service->update($request->validated());

        return response()->json([
            'data' => new ReviewResource($review),
        ]);
    }

    public function destroy(ReviewRequest $request) {}
}
