<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableFile extends Model
{
    protected $fillable = ['study_table_id', 'user_id', 'file_name', 'file_path', 'file_size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
