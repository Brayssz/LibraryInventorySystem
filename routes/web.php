<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Content\AppController;
use App\Http\Controllers\Content\BookController;
use App\Http\Controllers\Content\InventoryController;
use App\Http\Controllers\Content\SchoolController;
use App\Http\Controllers\Content\UserController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [LoginController::class, 'show'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AppController::class, 'showDashboard'])->name('dashboard');

Route::get('/users', [UserController::class, 'showUsers'])->name('users');

Route::get('/books', [BookController::class, 'showBooks'])->name('books');

Route::get('/schools', [SchoolController::class, 'showSchools'])->name('schools');

Route::get('/inventory', [InventoryController::class, 'showInventory'])->name('inventory');

Route::get('/division-total', [InventoryController::class, 'showDivisionTotal'])->name('division-total');
