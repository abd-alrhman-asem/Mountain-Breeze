<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { //English
       $restaurant= Category::create([
            'name'=>'Restaurant',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'We are thrilled to serve you and we hope you have a wonderful dining experience',
        ]);
        $chalets= Category::create([
            'name'=>'Chalets',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'With the best luxury spa, Gym and Pool experiences.',
        ]);
        $activeties= Category::create([
            'name'=>'Activeties',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'Never stop your daily activity',
        ]);
        $nature= Category::create([
            'name'=>'The Nature',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'Nature is a source of wonder and inspiration.',
        ]);
        $pool= Category::create([
            'name'=>'Pool',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'We hope you make a splash and have a great time with us',
        ]);
        $EVENTS= Category::create([
            'name'=>'RESORT EVENTS',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'Experience unforgettable moments with us',
        ]);
        $Conferences= Category::create([
            'name'=>'Conferences',
            'language_id'=>'2',
            'category_id'=>6,
            'summary'=>'Welcome to our conference, where industry leaders come together to share ideas and collaborate on solutions.',
        ]);
        $Sport_Events= Category::create([
            'name'=>'Sport Events',
            'language_id'=>'2',
            'category_id'=>6,
            'summary'=>'Never stop your daily activity',
        ]);
        $Spa= Category::create([
            'name'=>'SPA',
            'language_id'=>'2',
            'category_id'=>null,
            'summary'=>'Relax in our massage room',
        ]);

        //Arabic
        $restaurant_ar= Category::create([
            'name'=>'مطاعم',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'يسعدنا خدمتك ونأمل أن تحظى بتجربة طعام رائعة',
        ]);
        $chalets_ar= Category::create([
            'name'=>'شاليهات',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'تمتع بأجواء هادئة وومتعة برفقة من تحب',
        ]);
        $activeties_ar= Category::create([
            'name'=>'النشاطات',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'لا تتوقف أبدًا عن نشاطك اليومي',
        ]);
        $nature_ar= Category::create([
            'name'=>'الطبيعة',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'الطبيعة دائماَ تخطف الأنفاس لكنها تتميز بسحر إضافي في نسمة جبل',
        ]);
        $pool_ar= Category::create([
            'name'=>'المسابح',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'المقصد المثالي لعشاق السباحة',
        ]);
        $EVENTS_ar= Category::create([
            'name'=>'فعاليات المنتجع',
            'language_id'=>'1',
            'category_id'=>null,
            'summary'=>'عش اللحظات التي لا تنسى معنا',
        ]);
        $Conferences_ar= Category::create([
            'name'=>'مؤتمرات',
            'language_id'=>'1',
            'category_id'=>15,
            'summary'=>'مرحباً بكم في مؤتمرنا، حيث يجتمع قادة الصناعة معاً لتبادل الأفكار والتعاون في إيجاد الحلول',
        ]);
        $Sport_Events_ar= Category::create([
            'name'=>'أحداث رياضية',
            'language_id'=>'1',
            'category_id'=>15,
            'summary'=>'نرحب باستضافة جميع الأحداث الرياضية',
        ]);
    }
}
