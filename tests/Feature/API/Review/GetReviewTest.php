<?php

namespace Tests\Feature\API\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsDataWhenHasStatusCompleted()
    {
        //Create a review with status completed (default state on the factory)
        $review = Review::factory()->create();

        $response = $this->getReview();

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => 1,
                'title' => $review->title,
                'review' => $review->review,
                'cover_img' => $review->cover_img,
                'description' => $review->description,
                'authors' => $review->authors,
                'score' => $review->score,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at,
            ],
        ]);
    }

    public function testItReturnsStatus202WhenReviewHasStatusInProgress()
    {
        //Create a review with status processing
        $review = Review::factory()->create(['status' => 'processing']);

        $response = $this->getReview();

        $response->assertStatus(202);
    }

    private function getReview(): TestResponse
    {
        //Act
        $user = User::factory()->create();
        $this->actingAs($user);

        return $this->get('/api/review/1', ['Accept' => 'application/json']);
    }
}
