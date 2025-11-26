<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiPostTarget extends Model
{
    protected $fillable = [
        'api_post_id',
        'user_id',
        'status',
        'response_json',
    ];

    public function apiPost()
    {
        return $this->belongsTo(ApiPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


