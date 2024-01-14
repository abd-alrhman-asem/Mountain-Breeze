<?php

        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\API\{
            TagController,
            AuthController,
            FoodController,
            PostController,
            RoomController,
            UserController,
            SocialController,
            ArticleController,
            BookingController,
            GeneralController,
            ServiceController,
            CategoryController,
            LanguageController,
            RoomTypeController,
            HelpCenterController,
            FoodCategoryController,
            ImageController,
            VideoController
        };

        /*
        |--------------------------------------------------------------------------
        | API Routes
        |--------------------------------------------------------------------------
        |
        | Register API routes for the application. These routes are loaded by
        | the RouteServiceProvider and assigned to the "api" middleware group.
        |
        */

        // Authentication routes
        Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
            Route::post('/login', [AuthController::class, 'login']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
        });

        // Resourceful routes grouped by controllers
        Route::group(['middleware' => 'api'], function () {
            Route::apiResource('users', UserController::class);
            Route::apiResource('posts', PostController::class);
            Route::apiResource('languages', LanguageController::class);
            Route::apiResource('articles', ArticleController::class);
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('tags', TagController::class);
            Route::apiResource('socials', SocialController::class);
            Route::apiResource('helpcenter', HelpCenterController::class)->except(['destroy' ,'update']);
            Route::apiResource('generals', GeneralController::class)->except(['update']);
            Route::apiResource('roomtypes', RoomTypeController::class);
            Route::apiResource('foodcategories', FoodCategoryController::class);
            Route::apiResource('videos', VideoController::class);
            Route::apiResource('images', ImageController::class);
            Route::apiResource('foods', FoodController::class);
            Route::apiResource('bookings', BookingController::class)->except(['update']);
            Route::apiResource('rooms', RoomController::class);
            Route::apiResource('services', ServiceController::class);

        // get the deleted questions in help center
        Route::get('/deleted_questions', [HelpCenterController::class, 'deleted_questions']);
        // Delete a resource in HelpCenter (except 'destroy' and 'update' actions)
        Route::delete('/helpCenter', [HelpCenterController::class, 'destroy']);
        });

        /*
        * Additional custom routes
        */

        // Delete an article forcefully (permanent delete)
        Route::delete('/forceDestroy/{id}', [ArticleController::class, 'forceDestroy'])->name('forceDestroy');
        // Update a general resource
        Route::put('general_update/{id}', [GeneralController::class, 'update'])->name('general_update');
        // Get related articles for a specific article
        Route::get('/related_articles/{article}', [ArticleController::class, 'related_articles'])->name('related_articles');
        // Get a list of deleted rooms
        Route::get('/deleted_rooms', [RoomController::class, 'deleted_rooms'])->name('deleted_rooms');


        // Get a list of deleted articles
        Route::get('/deletedArticles', [ArticleController::class, 'deletedArticles'])->name('deletedArticles');


        // Fallback route for handling 404 errors
        Route::fallback(function () {
            return response()->json([
                'message' => 'Page Not Found.'
            ], 404);
        });

