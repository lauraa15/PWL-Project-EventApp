<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}