<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\BorrowingController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/books/statistics', [BookController::class, 'statistics']);
    
    // Books (everyone authenticated)
    Route::apiResource('books', BookController::class)
        ->except(['destroy']);


    // Delete book only for admin
   Route::middleware('admin')->group(function () {

    Route::delete('/books/{book}', [BookController::class, 'destroy']);

    // restore book
    Route::post('/books/{id}/restore', [BookController::class, 'restore']);
});


    // Members
    Route::apiResource('members', MemberController::class);


    // Borrowings
    Route::apiResource('borrowings', BorrowingController::class);

    
});