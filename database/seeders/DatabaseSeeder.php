<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Booking;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LanguageSeeder::class,
            CategorySeeder::class,
            FoodCategorySeeder::class,
            ServicesSeeder::class,
            TagsSeeder::class,
            RoomTypeSeeder::class,
            GeneralSeeder::class,
            SocialSeeder::class,
            PostSeeder::class,
            bookingSeeder::class,
            FoodSeeder::class,
            HelpCenterSeeder::class,

        ]);
    }
}
