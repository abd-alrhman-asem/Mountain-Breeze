<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'name'=>'#mountain_Breez',
            'language_id'=>2,
        ]);
        Tag::create([
            'name'=>'#Syrai',
            'language_id'=>2,
        ]);
        Tag::create([
            'name'=>'#Tourism',
            'language_id'=>2,
        ]);

        Tag::create([
            'name'=>'#جبل_النسيم',
            'language_id'=>1,
        ]);
        Tag::create([
            'name'=>'#سوريا',
            'language_id'=>1,
        ]);
        Tag::create([
            'name'=>'#سياحة',
            'language_id'=>1,
        ]);
        Tag::factory(10)->create();
    }
}
