<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestConnectionController;
use Illuminate\Support\Facades\DB;


Route::view('/register', 'auth.register')->name('register');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
// Role-specific dashboards with proper middleware
// Role-specific dashboard routes with JWT middleware
Route::middleware(['jwt'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('roles.admin.dashboard');
    })->middleware('jwt:admin')->name('admin.dashboard');
    
    Route::get('/finance/dashboard', function () {
        return view('roles.finance.dashboard');
    })->middleware('jwt:finance')->name('finance.dashboard');
    
    Route::get('/organizer/dashboard', function () {
        return view('roles.organizer.dashboard');
    })->middleware('jwt:organizer')->name('organizer.dashboard');
    
    Route::get('/member/dashboard', function () {
        return view('roles.member.dashboard');
    })->middleware('jwt:member')->name('member.dashboard');
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

// Organizer Routes
Route::middleware(['jwt:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('dashboard', function () {
        return view('organizer.dashboard');
    })->name('dashboard');

    // Events Management
    Route::get('events/{event}/scan-qr', [App\Http\Controllers\Organizer\EventController::class, 'showScanQR'])->name('events.scan-qr');
    Route::post('events/{event}/scan-qr', [App\Http\Controllers\Organizer\EventController::class, 'scanQR']);
    
    // Certificate Management
    Route::get('events/{event}/certificates', [App\Http\Controllers\Organizer\EventController::class, 'showCertificates'])->name('events.certificates');
    Route::post('events/{event}/certificates/upload', [App\Http\Controllers\Organizer\EventController::class, 'uploadCertificate'])->name('events.certificates.upload');
    Route::post('events/{event}/certificates/bulk-upload', [App\Http\Controllers\Organizer\EventController::class, 'bulkUploadCertificates'])->name('events.certificates.bulk-upload');

    // Event Sessions
    Route::get('events/{event}/sessions', [App\Http\Controllers\Organizer\EventSessionController::class, 'index'])->name('events.sessions.index');
    Route::post('events/{event}/sessions', [App\Http\Controllers\Organizer\EventSessionController::class, 'store'])->name('events.sessions.store');
    Route::put('events/{event}/sessions/{session}', [App\Http\Controllers\Organizer\EventSessionController::class, 'update'])->name('events.sessions.update');
    Route::delete('events/{event}/sessions/{session}', [App\Http\Controllers\Organizer\EventSessionController::class, 'destroy'])->name('events.sessions.destroy');

    // Resource Routes
    Route::resource('events', App\Http\Controllers\Organizer\EventController::class);
    Route::resource('certificates', App\Http\Controllers\Organizer\CertificateController::class)->only(['destroy']);
});

// Authentication routes
// Auth::routes();