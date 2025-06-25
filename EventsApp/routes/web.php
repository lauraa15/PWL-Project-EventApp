<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestConnectionController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\Organizer\AttendanceController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


// ✅ Public Routes
Route::view('/', 'welcome')->name('welcome');
Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/dashboard2', fn() => view('dashboard2'))->name('dashboard2');

Route::get('/db-test', function() {
    try {
        DB::connection()->getPdo();
        return "Database connected!";
    } catch (\Exception $e) {
        return "Could not connect to the database. Error: " . $e->getMessage();
    }
});
Route::get('/test-connection', [TestConnectionController::class, 'test']);
// Dashboard

// ✅ Role-based prefix (TANPA pengecekan hak akses)

// ---------------- ADMIN ----------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'roles.admin.dashboard')->name('dashboard');
    Route::view('/manage-user', 'roles.admin.manage-user')->name('manage-user');
    Route::get('/manage-user', function () {
        return view('roles.admin.manage-user');
    })->name('admin.manage-user');
    Route::get('/manage-events', function () {
        return view('roles.admin.manage-events');
    })->name('admin.manage-events');
});

// ---------------- FINANCE ----------------
Route::prefix('finance')->name('finance.')->group(function () {
    Route::view('/dashboard', 'roles.finance.dashboard')->name('dashboard');
    Route::view('/manage-finance', 'roles.finance.manage-finance')->name('manage-finance');
});

// ---------------- ORGANIZER ----------------
Route::prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    Route::get('/scan-qr', function () {
        return view('roles.organizer.scan-qr');
    })->name('scan-qr');

    Route::post('/scan-qr', function (Request $request) {
        try {
            $response = Http::post('http://localhost:3000/api/attendance/scan', $request->all());
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim data ke server Node.js.',
                'error' => $e->getMessage(),
            ], 500);
        }
    });
    Route::get('/attendance', function () {
    return view('roles.organizer.attendance');
})->name('organizer.attendance');
});
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



// ---------------- MEMBER ----------------
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Member\EventController::class, 'index'])->name('member.dashboard');
    Route::post('/dashboard/{event}/register', [App\Http\Controllers\Member\EventController::class, 'register'])->name('member.register');
});

// ✅ Components
Route::prefix('components')->name('component.')->group(function () {
    Route::view('accordion', 'components.accordion')->name('accordion');
});
