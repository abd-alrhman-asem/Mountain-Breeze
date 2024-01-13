<?php

namespace Database\Factories;

use App\Models\HelpCenter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<HelpCenter>
 */
class HelpCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = ['complaint','information','reservation','customer services','other'];
        return [
        'full_name' =>fake()->name,
        'phone' =>fake()->phoneNumber(),
        'email' => fake()->unique()->safeEmail(),
        'subject' =>Arr::random($subject),
        'message' => fake()->text
        ];
    }
}
