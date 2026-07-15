<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyTable extends Model
{
    protected $fillable = ['name', 'color', 'activity', 'user_id', 'password'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activeSessions()
    {
        return $this->hasMany(StudySession::class)->where('active', true);
    }
}
