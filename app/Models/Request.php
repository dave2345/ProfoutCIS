<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'user_id',
        'request_number',
        'type',
        'title',
        'description',
        'amount',
        'currency',
        'priority',
        'status',
        'payment_method',
        'category',
        'budget_code',
        'request_date',
        'required_by_date',
        'notes',
        'related_project_id',
        'supporting_documents',
        'line_items',
        'sla_days',
        'budget_allocated',
        'budget_remaining',
        'invoices',
        'quotes',
        'tags',
        'rejection_reason',
        'payment_date',
        'next_recurrence_date',
        'junior_approver_id',
        'junior_approval_at',
        'senior_approver_id',
        'senior_approval_at',
        'manager_approver_id',
        'manager_approval_at',
        'processed_by_id',
        'payment_processed_at',
        'related_tender_id',
        'previous_version_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'budget_allocated' => 'decimal:2',
        'budget_remaining' => 'decimal:2',
        'supporting_documents' => 'array',
        'invoices' => 'array',
        'quotes' => 'array',
        'tags' => 'array',
        'line_items' => 'array',
        'request_date' => 'date',
        'required_by_date' => 'date',
        'payment_date' => 'date',
        'next_recurrence_date' => 'date',
        'junior_approval_at' => 'datetime',
        'senior_approval_at' => 'datetime',
        'manager_approval_at' => 'datetime',
        'payment_processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'first_approved_at' => 'datetime',
        'second_approved_at' => 'datetime',
        'third_approved_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function juniorApprover()
    {
        return $this->belongsTo(User::class, 'junior_approver_id');
    }

    public function seniorApprover()
    {
        return $this->belongsTo(User::class, 'senior_approver_id');
    }

    public function managerApprover()
    {
        return $this->belongsTo(User::class, 'manager_approver_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }

    public function relatedProject()
    {
        return $this->belongsTo(Project::class, 'related_project_id');
    }

    public function relatedTender()
    {
        return $this->belongsTo(Tender::class, 'related_tender_id');
    }

    public function previousVersion()
    {
        return $this->belongsTo(Request::class, 'previous_version_id');
    }
}
