<?php

namespace Database\Factories;

use App\Models\booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<booking>
 */
class bookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $check_in = fake()->date();
        $check_out = date('Y-m-d', strtotime($check_in . ' + 5 days'));
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone'=>fake()->phoneNumber,
            'check_in'=> $check_in,
            'check_out'=> $check_out ,
            'description'=>fake()->text,
            'guest_number'=> fake()->numberBetween( 2 , 100),
            'room_type_id'=>  fake()->numberBetween(1 , 5 ) ,
        ];
    }
}
