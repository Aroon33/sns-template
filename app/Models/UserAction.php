<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
