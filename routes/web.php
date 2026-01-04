<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ScheduleController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');

Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedules.index');
Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedules.store');
Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');


Route::get('/', function () {
    return view('welcome');
});
