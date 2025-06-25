<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Attendance;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request) {
    $qrText = $request->qr_code_text;

    // Misal QR code-nya: REGISTRATION-5
    if (!Str::startsWith($qrText, 'REGISTRATION-')) {
        return response()->json(['success' => false, 'message' => 'QR tidak valid']);
    }

    $registrationId = (int) Str::after($qrText, 'REGISTRATION-');

    // Simpan kehadiran (contoh insert ke tabel `attendance`)
    Attendance::create([
        'registration_id' => $registrationId,
        'session_id' => 1, // bisa dynamic tergantung sesi
        'scan_time' => now(),
        'created_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Kehadiran dicatat']);
}
}
