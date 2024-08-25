<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\ReviewIdRequest;
use App\Http\Requests\Review\ReviewPostRequest;
use App\Http\Requests\Review\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Services\ReviewService\Exceptions\ReviewNotFoundException;
use App\Services\ReviewService\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(public ReviewService $service) {}

    /*
     * Get review by ID
     */
    public function review(ReviewIdRequest $request): JsonResponse
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

    /**
     * @throws ReviewNotFoundException
     */
    public function update(ReviewRequest $request): JsonResponse
    {
        $review = $this->service->update($request->validated());

        return response()->json([
            'data' => new ReviewResource($review),
        ]);
    }

    public function destroy(ReviewIdRequest $request): JsonResponse
    {
        $this->service->destroy($request->id);

        return response()->json([
            'message' => 'Review deleted',
        ], 204);
    }
}
