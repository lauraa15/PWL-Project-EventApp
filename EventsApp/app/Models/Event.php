<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_type_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'poster_image',
        'registration_fee',
        'registration_type',
        'max_participants',
        'current_participants',
        'registration_open_date',
        'registration_close_date',
        'certificate_type',
        'is_active',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_fee' => 'decimal:2',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'registration_open_date' => 'date',
        'registration_close_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user who created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the sessions for this event.
     */
    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }

    /**
     * Get the number of registrations for this event.
     */
    public function getRegistrationCountAttribute()
    {
        return $this->registrations()->count();
    }

    /**
     * Check if event has reached maximum participants.
     */
    public function getIsFullAttribute()
    {
        return $this->registration_count >= $this->max_participants;
    }

    /**
     * Check if an event is upcoming.
     */
    public function getIsUpcomingAttribute()
    {
        return $this->event_date->isFuture();
    }

    /**
     * Get formatted registration fee.
     */
    public function getFormattedFeeAttribute()
    {
        return 'Rp ' . number_format($this->registration_fee, 0, ',', '.');
    }
}