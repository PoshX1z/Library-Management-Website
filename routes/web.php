<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ContactController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');

Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedules.index');
Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedules.store');
Route::put('/schedule/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');


Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
Route::post('/staffs', [StaffController::class, 'store'])->name('staffs.store');
Route::put('/staffs/{id}', [StaffController::class, 'update'])->name('staffs.update');
Route::delete('/staffs/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');


Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

Route::get('/', function () {
    return view('welcome');
});
