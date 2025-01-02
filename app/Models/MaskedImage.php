<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaskedImage extends Model
{
    protected $fillable = [
        'analysis_id',
        'file_path',
        'file_data'
    ];
}
