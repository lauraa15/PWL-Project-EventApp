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
        'name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'poster_image',
        'registration_fee',
        'max_participants',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'registration_fee' => 'decimal:2',
        'max_participants' => 'integer',
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