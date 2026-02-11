<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'project_id','order_name','budget_amount',
        'extracted_budget','document_path','is_verified','verified_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
