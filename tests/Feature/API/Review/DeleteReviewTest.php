<?php

namespace Tests\Feature\API\Review;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testItDeletesAReview()
    {
        $review = Review::factory()->create();

        $response = $this->deleteReview($review->id);

        $this->assertDatabaseMissing('reviews', $review->toArray());

        $response->assertStatus(204);
    }

    private function deleteReview(int $reviewId): TestResponse
    {
        //Act
        $user = User::factory()->create();
        $this->actingAs($user);

        return $this->deleteJson('/api/review/'.$reviewId, [], ['Accept' => 'application/json']);
    }
}
