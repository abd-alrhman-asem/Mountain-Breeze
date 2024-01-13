<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'description' => fake()->text,
            'summary' => fake()->sentence,
            'price_per_night' => fake()->numberBetween(10, 10000),
            'guest_number' => fake()->numberBetween(2, 20),
            'location' => fake()->word,
            'room_type_id' => fake()->numberBetween(1, 10),
            'language_id' => fake()->numberBetween(1, 2)
        ];
    }
}
