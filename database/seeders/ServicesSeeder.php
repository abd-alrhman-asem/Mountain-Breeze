<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name'=>'4 Persons',
            'language_id'=>2,
        ]);
        Service::create([
            'name'=>'Room Services',
            'language_id'=>2,
        ]);
        Service::create([
            'name'=>'Kingsize Bed',
            'language_id'=>2,
        ]);
        Service::create([
            'name'=>'TV',
            'language_id'=>2,
        ]);

        Service::create([
            'name'=>'4 أشخاص',
            'language_id'=>1,
        ]);
        Service::create([
            'name'=>'خدمة الغرف ',
            'language_id'=>1,
        ]);
        Service::create([
            'name'=>'أسرة ذو حجم كبير',
            'language_id'=>1,
        ]);
        Service::create([
            'name'=>'تلفاز',
            'language_id'=>1,
        ]);

    }
}
