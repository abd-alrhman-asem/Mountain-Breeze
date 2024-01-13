<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMedia::create([
            'name'=>'mountain_email',
            'link'=>'mountainBrezze@gmail.com'
        ]);
        SocialMedia::factory(10)->create();

    }
}
