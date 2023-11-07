<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\HelpCenterController;
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

Route::get('/deleted_articles',[ArticleController::class,'deleted_articles'])->name('deleted_articles');

Route::get('/related_articles/{id}',[ArticleController::class,'related_articles'])->name('related_articles');

Route::apiResource('tags',TagController::class);

Route::apiResource('helpcenter',HelpCenterController::class);


Route::apiResource('generals',GeneralController::class);
