<?php

namespace App\Jobs;

use App\Clients\BooksClient\BooksClientInterface;
use App\Clients\BooksClient\Enums\CoverSize;
use App\Models\Review;
use App\Services\ReviewService\ReviewService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessReviewInfo implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

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
    public function handle(BooksClientInterface $booksClient, ReviewService $service): void
    {
        $reviewData = $booksClient->reviewData($this->response, CoverSize::Medium);

        $service->updateWithReviewData($this->id, $reviewData);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Review::find($this->id)->update([
            'status' => 'error',
        ]);
    }
}
