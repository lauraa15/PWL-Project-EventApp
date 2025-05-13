<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'registration_code',
        'qr_code',
        'status',
    ];

    /**
     * Get the user who registered.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that was registered for.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the payment for this registration.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the attendance for this registration.
     */
    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    /**
     * Get the certificate for this registration.
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Check if registration is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if participant attended the event.
     */
    public function hasAttended()
    {
        return $this->attendance && $this->attendance->check_in_time !== null;
    }

    /**
     * Check if certificate has been issued.
     */
    public function hasCertificate()
    {
        return $this->certificate !== null;
    }
}