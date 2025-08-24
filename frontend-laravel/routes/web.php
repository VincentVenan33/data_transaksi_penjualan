<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;

Route::get('/', [DashboardController::class, 'index']);
Route::resource('products', ProductController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('customers', CustomerController::class);
