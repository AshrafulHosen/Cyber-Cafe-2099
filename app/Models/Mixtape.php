<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mixtape extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'title',
        'channel_title',
        'thumbnail_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
