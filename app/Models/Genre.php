<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_genres');
    }
}
