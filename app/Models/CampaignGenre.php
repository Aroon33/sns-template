<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignGenre extends Model
{
    use HasFactory;

    protected $table = 'campaign_genres';

    protected $fillable = [
        'campaign_id',
        'genre_id',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}

