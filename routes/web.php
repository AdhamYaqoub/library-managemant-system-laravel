<?php

use App\Http\Controllers\BookController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

// Route::get('/books', [BookController::class, 'index']);
// Route::get('/members', [MemberController::class, 'index']);
// Route::get('/borrowings', [BorrowingController::class, 'index']);

// Route::get('/books/create', [BookController::class, 'create']);
// Route::post('/books', [BookController::class, 'store']);

// Route::get('/books/{book}/edit', [BookController::class, 'edit']);
// Route::put('/books/{book}', [BookController::class, 'update']);

// Route::delete('/books/{book}', [BookController::class, 'destroy']);

// Route::get('/members/create', [MemberController::class, 'create']);
// Route::post('/members', [MemberController::class, 'store']);

// Route::get('/members/{member}/edit', [MemberController::class, 'edit']);
// Route::put('/members/{member}', [MemberController::class, 'update']);

// Route::delete('/members/{member}', [MemberController::class, 'destroy']);

// Route::get('/borrowings/create', [BorrowingController::class, 'create']);

// Route::post('/borrowings', [BorrowingController::class, 'store']);

// Route::put(
//     '/borrowings/{borrowing}/return',
//     [BorrowingController::class, 'returnBook']
// );

// Route::get('/books/search', [BookController::class, 'search']);

// Route::get('/books/category', [BookController::class, 'searchCategory']);

// Route::get('/books/sort/title', [BookController::class, 'sortTitle']);
// Route::get('/books/sort/year', [BookController::class, 'sortYear']);

// Route::get('/statistics', [BookController::class, 'statistics']);

Route::get('/', function () {
    return view('welcome');
});
