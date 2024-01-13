<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title ,
            'summary' => fake()->sentence,
            'description' =>fake()->text,
            'language_id' => 2 ,
            'category_id' => fake()->numberBetween(1  ,9 )  ,
        ];
    }
}
