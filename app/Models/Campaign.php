<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;
use App\Models\CampaignTarget;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'lp_url',
    'daily_budget_max',
    'desired_post_count',
    'posts_count',
    'posters_count',
    'likes_count',
    'retweets_count',
    'views_count',
    'total_ad_cost',
    'today_ad_cost',
];


    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    /**
     * 案件に紐づくジャンル
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'campaign_genres');
    }

    /**
     * 案件に紐づく「確定ターゲット」一覧
     */
    public function targets()
    {
        return $this->hasMany(CampaignTarget::class);
    }
}





