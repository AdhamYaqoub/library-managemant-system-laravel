<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\AiController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);


    /*
    |--------------------------------------------------------------------------
    | Books - Authenticated Users
    |--------------------------------------------------------------------------
    */

    Route::apiResource('books', BookController::class)
        ->only(['index', 'show']);


    /*
    |--------------------------------------------------------------------------
    | Member
    |--------------------------------------------------------------------------
    */

    Route::middleware('member')->group(function () {

        Route::apiResource('borrowings', BorrowingController::class)
            ->only(['store']);

        Route::post(
            '/borrowings/{id}/return',
            [BorrowingController::class, 'returnBook']
        );
    });


    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

        /*
        | Books Management
        */

        Route::apiResource('books', BookController::class)
            ->only(['store', 'update', 'destroy']);

        Route::post(
            '/books/{id}/restore',
            [BookController::class, 'restore']
        );

        Route::get(
            '/books/statistics',
            [BookController::class, 'statistics']
        );


        /*
        | Members Management
        */

        Route::apiResource('members', MemberController::class);


        /*
        | Borrowings Management
        */

        Route::apiResource('borrowings', BorrowingController::class)
            ->only(['index', 'show', 'update', 'destroy']);


        Route::get('/books/{id}/history', [BookController::class, 'history']);
    });

    Route::post('/ai/chat', [AiController::class, 'chat']);


    Route::get('/php-info', function () {
    return [
        'max_execution_time' => ini_get('max_execution_time'),
        'loaded_ini' => php_ini_loaded_file(),
        'php_version' => PHP_VERSION,
    ];
});
});