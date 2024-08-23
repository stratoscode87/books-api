<?php

namespace Tests\Feature\API;

use App\Clients\BooksClient\OpenLibraryClient\Exceptions\WorkNotFoundException;
use App\Services\ReviewService;
use Mockery\MockInterface;
use Tests\TestCase;

class PostReviewTest extends TestCase
{
    public function testWorkIdNotFound()
    {
        $this->partialMock(ReviewService::class, function (MockInterface $mock) {
            $mock->shouldReceive('postReview')->once()
                ->andThrow(new WorkNotFoundException('Work ID not found.'));
        });

        $response = $this->post('/api/review', [
            'work_id' => 'OL27448W',
            'review' => 'test',
            'score' => 6,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'Work ID not found.',
        ]);
    }
}
