<?php

namespace Tests\Feature\API\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PutReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testItUpdatesAReviewCorrectly()
    {
        //Save a review
        $review = Review::factory()->create();
        //New review data
        $newReviewData = Review::factory()->make()->toArray();

        //Request body
        $body = [
            'id' => $review->id,
            'work_id' => $newReviewData['work_id'],
            'title' => $newReviewData['title'],
            'review' => $newReviewData['review'],
            'cover_img' => $newReviewData['cover_img'],
            'description' => $newReviewData['description'],
            'authors' => $newReviewData['authors'],
            'score' => $newReviewData['score'],
        ];

        $response = $this->putReview($review->id, $body);

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', $body);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => $body,
        ]);
    }

    private function putReview(int $reviewId, array $body): TestResponse
    {
        //Act
        $user = User::factory()->create();
        $this->actingAs($user);

        return $this->put('/api/review/{$reviewId}', $body, ['Accept' => 'application/json']);
    }
}
