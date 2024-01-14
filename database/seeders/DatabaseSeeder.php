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
            bookingSeeder::class,
            CategorySeeder::class,
            FoodCategorySeeder::class,
            FoodSeeder::class,
            GeneralSeeder::class,
            HelpCenterSeeder::class,
            LanguageSeeder::class,
            PostSeeder::class,
            RoomSeeder::class,
            RoomTypeSeeder::class,
            ServicesSeeder::class,
            SocialSeeder::class,
            TagsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
