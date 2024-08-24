<?php

namespace App\Services;

use App\Clients\BooksClient\BooksClientInterface;
use App\Enums\Review\ReviewStatus;
use App\Http\Resources\ReviewResource;
use App\Jobs\ProcessReviewInfo;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewService
{
    public function __construct(private BooksClientInterface $booksClient) {}

    public function getReview(int $id): Review
    {
        return Review::find($id);
    }

    public function getReviewResponse(Review $review): JsonResponse
    {
        return match ($review->status) {
            ReviewStatus::Processing->value => response()->json(['status' => 'processing...'], 202),
            ReviewStatus::Completed->value => response()->json(['data' => new ReviewResource($review)]),
            ReviewStatus::Error->value => response()->json([
                'error' => 'Something went wrong updating the data',
                'data' => new ReviewResource($review)]),
        };
    }

    public function postReview(array $validatedReview): int
    {
        //Fetch data from Open Library and check if the work_id exists
        $response = $this->booksClient->fetchWork($validatedReview['work_id']);
        //Save request on DB if work_id exists
        $reviewId = $this->saveReviewRequest($validatedReview);
        //Fetch and save full data
        ProcessReviewInfo::dispatch($reviewId, $response);

        return $reviewId;
    }

    private function saveReviewRequest(array $validatedReview): int
    {
        $review = Review::create([
            'work_id' => $validatedReview['work_id'],
            'review' => $validatedReview['review'],
            'score' => $validatedReview['score'],
        ]);

        return $review->id;
    }

    public function putRequest(array $validatedReview): Review
    {
        $review = Review::find($validatedReview['id']);
        $review->update([
            'work_id' => $validatedReview['work_id'],
            'title' => $validatedReview['title'],
            'description' => $validatedReview['description'],
            'cover_img' => $validatedReview['cover_img'],
            'authors' => $validatedReview['authors'],
            'score' => $validatedReview['score'],
        ]);

        return $review->refresh();
    }
}
