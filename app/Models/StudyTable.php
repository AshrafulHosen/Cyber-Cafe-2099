<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyTable extends Model
{
    protected $fillable = ['name', 'color', 'activity'];

    public function activeSessions()
    {
        return $this->hasMany(StudySession::class)->where('active', true);
    }
}
