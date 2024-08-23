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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'cover_img' => $this->faker->word(),
            'description' => $this->faker->text(),
            'authors' => $this->faker->word(),
            'status' => $this->faker->word(),
            'publishedAt' => Carbon::now(),
        ];
    }
}
