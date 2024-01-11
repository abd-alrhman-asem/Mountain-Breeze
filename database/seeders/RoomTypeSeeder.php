<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomType::create([
            'name'=>'Duplex Room',
            'language_id'=>2,
        ]);

        RoomType::create([
            'name'=>'غرفة دوبلكس',
            'language_id'=>1,
        ]);
        RoomType::factory(5)->create();
    }
}
