<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Content\AppController;
use App\Http\Controllers\Content\BookController;
use App\Http\Controllers\Content\BookRequestController;
use App\Http\Controllers\Content\InventoryController;
use App\Http\Controllers\Content\PDFController;
use App\Http\Controllers\Content\ReportController;
use App\Http\Controllers\Content\ReturnController;
use App\Http\Controllers\Content\SchoolController;
use App\Http\Controllers\Content\UserController;

Route::get('/', [AppController::class, 'landingPage']);

Route::get('/login', [LoginController::class, 'show'])->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {
      
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [AppController::class, 'showDashboard'])->name('dashboard');

    Route::get('/users', [UserController::class, 'showUsers'])->name('users');

    Route::get('/books', [BookController::class, 'showBooks'])->name('books');

    Route::get('/schools', [SchoolController::class, 'showSchools'])->name('schools');

    Route::get('/inventory', [InventoryController::class, 'showInventory'])->name('inventory');

    Route::get('/division-total', [InventoryController::class, 'showDivisionTotal'])->name('division-total');

    Route::get('/generate-report', [ReportController::class, 'exportInventoryReport'])->name('generate-report');

    Route::get('/get-report-data', [ReportController::class, 'showGenerateReport'])->name('get-report-data');

    Route::get('/request-form', [AppController::class, 'showRequestForm'])->name('request-form');

    Route::get('/book-request', [BookRequestController::class, 'showRequests'])->name('book-request');

    Route::get('/available-books', [BookRequestController::class, 'getAvailableBooks'])->name('available-books');

    Route::get('/borrowed-books', [ReturnController::class, 'showBorrowTransactions'])->name('borrowed-books');

    Route::get('/delivery-transactions-report', [ReportController::class, 'showDeliveryTransactions'])->name('delivery-transactions-report');

    Route::get('/books-distribution-report', [ReportController::class, 'showBooksDistribution'])->name('books-distribution-report');

    Route::get('/book-requests-report', [ReportController::class, 'showBookRequests'])->name('book-requests-report');

    Route::get('/borrowing-transaction-report', [ReportController::class, 'showBorrowingTransaction'])->name('borrowing-transaction-report ');

    Route::get('/returned-books-report', [ReportController::class, 'showReturnedBooks'])->name('returned-books-report');

    Route::get('/book-inventory-report', [ReportController::class, 'showBookInventory'])->name('book-inventory-report');


    Route::get('/generate-delivery-transactions-report', [PDFController::class, 'showDeliveryTransactions'])->name('generate-delivery-transactions-report');

    Route::get('/generate-book-requests-report', [PDFController::class, 'showBookRequests'])->name('generate-book-requests-report');

    Route::get('/generate-books-distribution-report', [PDFController::class, 'showBooksDistribution'])->name('generate-books-distribution-report');

    Route::get('/generate-borrowing-transaction-report', [PDFController::class, 'showBorrowingTransaction'])->name('generate-borrowing-transaction-report ');

    Route::get('/generate-returned-books-report', [PDFController::class, 'showReturnedBooks'])->name('generate-returned-books-report');

    Route::get('/generate-book-inventory-report', [PDFController::class, 'showBookInventory'])->name('generate-book-inventory-report');

    Route::get('/monthly-transaction-data', [AppController::class, 'getMonthlyTransactionData'])->name('monthly-transaction-data');
});
