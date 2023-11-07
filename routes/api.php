<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\SocialController;
use App\Http\Controllers\API\TagController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::apiResource('articles',ArticleController::class);

Route::get('/deleted',[ArticleController::class,'deleted_articles']);

Route::apiResource('tags',TagController::class);

Route::apiResource('socials',SocialController::class);
