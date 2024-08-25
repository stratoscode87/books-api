<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'review' => $this->faker->paragraph(),
            'description' => $this->faker->text(),
            'cover_img' => $this->faker->url(),
            'authors' => $this->faker->word(),
            'score' => $this->faker->numberBetween(1, 10),
            'work_id' => 'FAKEWID',
            'status' => 'completed',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
