<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestConnectionController;
use Illuminate\Support\Facades\DB;

Route::get('/db-test', function() {
    try {
        DB::connection()->getPdo();
        return "Database connected!";
    } catch (\Exception $e) {
        return "Could not connect to the database. Error: " . $e->getMessage();
    }
});
// Dashboard
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard2', function () {
    return view('dashboard2');
})->name('dashboard2');

// Components
Route::prefix('components')->group(function () {
    Route::get('accordion', function () {
        return view('components.accordion');
    })->name('component.accordion');
    
    // Other component routes
});

Route::get('/test-connection', [TestConnectionController::class, 'test']);
// Authentication routes
// Auth::routes();