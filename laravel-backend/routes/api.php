<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class)->names('legacy.products');

Route::post('/payroll', [HumanResourceController::class, 'payroll']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::apiResource('posts', PostController::class)->only(['index', 'store']);
