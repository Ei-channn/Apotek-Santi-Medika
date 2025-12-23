<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ReportController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware(['auth','role:admin,kasir'])->group(function () {
    Route::get('/reports/daily', [ReportController::class, 'dailyReport'])->name('reports.daily');
    Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])->name('reports.monthly');
});


Route::middleware(['auth','role:admin,kasir'])->group(function () {
    Route::get('/medicines/search', [MedicineController::class, 'search'])
        ->name('medicines.search');
});

Route::get('/categories/search', [CategoryController::class, 'search'])
    ->name('categories.search');

Route::get('/medicines/search', [MedicineController::class, 'search'])
    ->name('medicines.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->middleware('auth')
        ->name('transactions.index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::delete('/transactions/{transaction}', 
        [TransactionController::class, 'destroy']
    )->name('transactions.destroy');
});

Route::middleware(['auth','role:admin,kasir'])->group(function () {
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('medicines', MedicineController::class);
});

