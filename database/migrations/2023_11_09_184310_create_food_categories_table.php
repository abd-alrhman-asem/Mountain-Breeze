<?php

use Illuminate\Support\Facades\Schema;
use Database\Seeders\FoodCategorySeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->references('id')->on('languages');
            $table->string('name');
            $table->string('summary');
            $table->timestamps();
        });
        $seeder = new FoodCategorySeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_categories');
    }
};
