<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'amount',
        'payment_date',
        'verified_by',
        'verification_date',
        'status',
        'notes',
        'created_at',
        'updated_at',
        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_date' => 'datetime',
        'verification_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the registration associated with this payment.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the user who verified this payment.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if the payment is verified.
     */
    public function isVerified()
    {
        return $this->status === 'verified';
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}