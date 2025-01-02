<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingImage extends Model
{
        
    protected $fillable = [
        'analysis_id',
        'file_path',
        'name',
        'segment_number'
    ];
}
