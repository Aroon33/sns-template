<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiPost extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'body',
        'hashtags',
        'image_path',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targets()
    {
        return $this->hasMany(ApiPostTarget::class);
    }
}


