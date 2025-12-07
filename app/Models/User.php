<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Attributes hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function profile()
{
    return $this->hasOne(UserProfile::class);
}
public function clientProfile()
{
    return $this->hasOne(\App\Models\ClientProfile::class);
}
// app/Models/User.php
public function metric()
{
    return $this->hasOne(UserMetric::class);
}
// app/Models/User.php
public function apiPostTargets()
{
    return $this->hasMany(ApiPostTarget::class);
}






public function actions()
{
    return $this->hasMany(\App\Models\UserAction::class);
}



public function genres()
{
    return $this->belongsToMany(Genre::class, 'user_genres');
}

}
