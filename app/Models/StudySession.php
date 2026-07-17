<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
{
    protected $fillable = ['study_table_id', 'user_id', 'active', 'started_at'];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyTable()
    {
        return $this->belongsTo(StudyTable::class);
    }
}
