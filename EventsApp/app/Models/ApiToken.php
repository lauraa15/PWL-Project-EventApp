<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'last_used_at',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function isValid()
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }
}
