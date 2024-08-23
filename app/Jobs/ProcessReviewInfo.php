<?php

namespace App\Jobs;

use App\Clients\BooksClient\BooksClientInterface;
use App\Clients\BooksClient\Enums\CoverSize;
use App\Models\Review;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessReviewInfo implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly int $id,
        private readonly object $response,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(BooksClientInterface $booksClient): void
    {
        $reviewData = $booksClient->reviewData($this->response, CoverSize::Medium);

        $review = Review::find($this->id);

        $review->update([
            ...$reviewData,
            'status' => 'completed',
        ]);
    }
}
