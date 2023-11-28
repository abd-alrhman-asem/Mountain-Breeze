<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodCategory::create([
            'name'=>'مأكولات غربية',
            'language_id'=>1,
            'summary'=>'أشهى الأطباق الغربية',
        ]);
        FoodCategory::create([
            'name'=>'مأكولات شرقية',
            'language_id'=>1,
            'summary'=>'أشهى الأطباق الشرقية',
        ]);
        FoodCategory::create([
            'name'=>'مأكولات تقليدية',
            'language_id'=>1,
            'summary'=>'أشهى الأطباق التقليدية',
        ]);
        FoodCategory::create([
            'name'=>'Western Food',
            'language_id'=>2,
            'summary'=>'The most delicious western dishes.',
        ]);
        FoodCategory::create([
            'name'=>'Traditional Food',
            'language_id'=>2,
            'summary'=>'The most delicious Traditional dishes.',
        ]);
        FoodCategory::create([
            'name'=>'Oriental Food',
            'language_id'=>2,
            'summary'=>'The most delicious Oriental dishes.',
        ]);

    }
}
