<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestConnectionController;
use Illuminate\Support\Facades\DB;


Route::view('/register', 'auth.register')->name('register');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::middleware(['web', \App\Http\Middleware\EnsureTokenExists::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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

Route::get('/admin/dashboard', function () {
    return view('roles.admin.dashboard');
})->name('admin.dashboard');
Route::get('/finance/dashboard', function () {
    return view('roles.finance.dashboard');
})->name('finance.dashboard');
Route::get('/member/dashboard', function () {
    return view('roles.member.dashboard');
})->name('member.dashboard');
Route::get('/organizer/dashboard', function () {
    return view('roles.organizer.dashboard');
})->name('organizer.dashboard');


Route::get('/admin/manage-user', function () {
    return view('roles.admin.manage-user');
})->name('admin.manage-user');



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