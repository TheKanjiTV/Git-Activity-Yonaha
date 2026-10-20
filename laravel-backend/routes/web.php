<?php

use App\Http\Controllers\KenjiController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function (): RedirectResponse {
    return redirect()->away(config('app.frontend_url'));
})->name('dashboard');

Route::get('/kenji', [KenjiController::class, 'index']);

Route::get('/read', [KenjiController::class, 'read']);

Route::get('/greet/{name}', [KenjiController::class, 'greet']);
