<?php

namespace Feature\API\Review;

use App\Clients\BooksClient\BooksClientInterface;
use App\Clients\BooksClient\OpenLibraryClient\Exceptions\WorkNotFoundException;
use App\Services\ReviewService\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use Tests\TestCase;

class PostReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testInitiallyHasPendingStatus(): void
    {
        Queue::fake();

        $this->mock(BooksClientInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('fetchWork')
                ->once()
                ->andReturn((object) [
                    'status' => 'ok',
                ], 200);
        });

        $response = $this->postReview();

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', [
            'work_id' => 'OL27448W',
            'review' => 'test',
            'score' => 6,
        ]);

        $response->assertStatus(202);
        $response->assertExactJson([
            'status' => 'processing...',
        ]);
    }

    public function testWorkIdNotFound(): void
    {
        $this->partialMock(ReviewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('postReview')->once()
                ->andThrow(new WorkNotFoundException('Work ID not found.'));
        });

        $response = $this->postReview();

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'Work ID not found.',
        ]);
    }

    private function postReview(string $workId = 'OL27448W', string $review = 'test', int $score = 6): TestResponse
    {
        return $this->post('/api/review', [
            'work_id' => $workId,
            'review' => $review,
            'score' => $score,
        ], ['Accept' => 'application/json']);
    }
}
