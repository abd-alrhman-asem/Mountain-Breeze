<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\RoomTypeController;
use App\Http\Controllers\API\HelpCenterController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\FoodCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('posts',PostController::class);


Route::apiResource('articles',ArticleController::class);

Route::apiResource('categories',CategoryController::class);

Route::get('/deleted_articles',[ArticleController::class,'deleted_articles'])->name('deleted_articles');

Route::get('/related_articles/{id}',[ArticleController::class,'related_articles'])->name('related_articles');

Route::apiResource('tags',TagController::class);

Route::apiResource('socials',SocialController::class);

Route::apiResource('helpcenter',HelpCenterController::class);

Route::apiResource('generals',GeneralController::class);

Route::apiResource('roomtypes',RoomTypeController::class);

Route::apiResource('foodcategories',FoodCategoryController::class);


Route::apiResource('foods', FoodController::class);

Route::apiResource('bookings',BookingController::class)->except(['update']);

Route::apiResource('rooms', RoomController::class);

Route::get('/deleted_rooms',[RoomController::class,'deleted_rooms'])->name('deleted_rooms');

Route::apiResource('services', ServiceController::class);
