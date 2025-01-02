<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraySummary extends Model
{
    protected $fillable = [
        'analysis_id',
        'file_path'
    ];
}
