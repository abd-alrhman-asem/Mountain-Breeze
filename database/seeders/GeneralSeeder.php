<?php

namespace Database\Seeders;

use App\Models\General;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        General::create([
            'name'=>'Mountain Breeze',
            'value'=>'Syria, Kurdaha, Alqlelaa village',
            'language_id'=>2,
        ]);
        General::create([
            'name'=>'Main Hotel Contact Numbers:',
            'value'=>'+963 944 000 111 /  (041) 211 541',
            'language_id'=>2,
        ]);
        General::create([
            'name'=>'Self Parking:',
            'value'=>'Standard parking rates to apply',
            'language_id'=>2,
        ]);
        General::create([
            'name'=>'CONTACT',
            'value'=>' 944 000 111',
            'language_id'=>2,
        ]);



        General::create([
            'name'=>'نسمة جبل ',
            'value'=>'سوريا، القرداحة،قرية القليلة',
            'language_id'=>1,
        ]);
        General::create([
            'name'=>'أرقام اتصال الفندق الرئيسي',
            'value'=>'+963 944 000 111 /  (041) 211 541',
            'language_id'=>1,
        ]);
        General::create([
            'name'=>'مواقغ السيارات:',
            'value'=>'تطبيق معايير ركن السيارات ',
            'language_id'=>1,
        ]);

    }
}
