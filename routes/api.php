<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\RoomTypeController;
use App\Http\Controllers\API\HelpCenterController;
use App\Http\Controllers\API\FoodCategoryController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\VideoController;

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
Route::apiResource('languages',LanguageController::class);
Route::apiResource('posts',PostController::class);


Route::apiResource('users',UserController::class);

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});

Route::group(['middleware' => 'api','prefix' => 'auth'],function ($router) {
    Route::post('/login', [AuthController::class,'login']);
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/me', [AuthController::class,'me']);
});

Route::apiResource('articles',ArticleController::class);

Route::apiResource('categories',CategoryController::class);

Route::get('/deleted_articles',[ArticleController::class,'deleted_articles'])->name('deleted_articles');

Route::get('/related_articles/{id}',[ArticleController::class,'related_articles'])->name('related_articles');

Route::apiResource('tags',TagController::class);

Route::apiResource('socials',SocialController::class);

Route::apiResource('helpcenter',HelpCenterController::class)->except(['destroy','update']);

Route::apiResource('generals',GeneralController::class)->except(['update']);

Route::put('general_update/{id}',[GeneralController::class,'update'])->name('general_update');

Route::apiResource('roomtypes',RoomTypeController::class);

Route::apiResource('foodcategories',FoodCategoryController::class);
Route::apiResource('videos',VideoController::class);
Route::apiResource('images',ImageController::class);

Route::apiResource('foods', FoodController::class);


Route::apiResource('bookings',BookingController::class)->except(['update']);

Route::apiResource('rooms', RoomController::class);

Route::delete('/delete',[HelpCenterController::class,'destroy'])->name('delete');

Route::get('/deleted_rooms',[RoomController::class,'deleted_rooms'])->name('deleted_rooms');

Route::apiResource('services', ServiceController::class);
