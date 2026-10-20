<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.data');

Route::get(
    '/products/queries',
    [ProductController::class, 'query']
)->name('products.queries');

Route::get(
    '/products',
    [ProductController::class, 'index']
)->name('products.index');

Route::get(
    '/products/{product}',
    [ProductController::class, 'show']
)->name('products.show');

Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->name('categories.show');
