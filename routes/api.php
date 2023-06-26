<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:sanctum'])->group(function(){
    Route::prefix('/articles')->group(function () {
        Route::get('', [ArticleController::class, 'index']);
        Route::get('/for-you', [ArticleController::class, 'indexBasedOnPreferences']);
        Route::get('/categories', [ArticleController::class, 'distinctCategories']);
        Route::get('/sources', [ArticleController::class, 'distinctSources']);
        Route::get('/authors', [ArticleController::class, 'distinctAuthors']);
    });
    Route::prefix('/users')->group(function () {
        Route::put('/preferences', [UserController::class, 'updatePreferences']);
        Route::get('/preferences', [UserController::class, 'getPreferences']);
    });
});

