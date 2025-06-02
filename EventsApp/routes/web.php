<?php

use Illuminate\Support\Facades\Route;
// use 

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

// Authentication routes
Auth::routes();