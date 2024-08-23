<?php

namespace App\Services;

use App\Clients\BooksClient\BooksClientInterface;
use App\Jobs\ProcessReviewInfo;
use App\Models\Review;

class ReviewService
{
    public function __construct(private readonly BooksClientInterface $booksClient) {}

    public function postReview(array $validatedReview): int
    {
        //Fetch data from Open Library and check if the work_id exists
        $response = $this->booksClient->fetchWork($validatedReview['work_id']);
        //Save request on DB if work_id exists
        $reviewId = $this->saveReviewRequest($validatedReview);
        //Fetch and save full data
        ProcessReviewInfo::dispatch($reviewId, $validatedReview['work_id'], $response);

        return $reviewId;
    }

    private function saveReviewRequest(array $reviewRequest): int
    {
        $review = Review::create([
            'work_id' => $reviewRequest['work_id'],
            'review' => $reviewRequest['review'],
            'score' => $reviewRequest['score'],
        ]);

        return $review->id;
    }
}
