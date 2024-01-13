<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Arabic= Language::create([
            'name'=>'Ar',
        ]);
        $English= Language::create([
            'name'=>'En',
        ]);
        Language::factory(10)->create();
    }
}
