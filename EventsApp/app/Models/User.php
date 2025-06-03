<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'role_id',
        'name', 
        'email', 
        'password', 
        'phone_number',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
    public function hasAnyRole($roleNames)
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
   public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }
}