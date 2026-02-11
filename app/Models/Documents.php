<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $fillable = [
        'module', 'module_id', 'file_path', 'file_type', 'uploaded_by'
    ];
}
