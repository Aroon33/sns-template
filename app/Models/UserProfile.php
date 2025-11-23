<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'x_username',
        'x_user_id',
        'followers_count',
        'location',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

