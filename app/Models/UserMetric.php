<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMetric extends Model
{
    protected $fillable = [
        'user_id',
        'followers_count',
        'posts_count',
        'likes_count',
        'retweets_count',
        'impressions_count',
        'last_synced_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



