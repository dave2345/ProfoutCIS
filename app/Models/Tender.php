<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = [
        'project_id','tender_name','tender_value',
        'document_path','is_verified','verified_by'
    ];
}
