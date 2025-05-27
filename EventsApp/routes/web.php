<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestConnectionController;

// Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Components
Route::prefix('components')->group(function () {
    Route::get('accordion', function () {
        return view('components.accordion');
    })->name('component.accordion');
    
    // Other component routes
});

Route::get('/test-connection', [TestConnectionController::class, 'test']);
// Authentication routes
Auth::routes();