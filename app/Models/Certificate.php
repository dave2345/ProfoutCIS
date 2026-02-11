<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'certificate_number',
        'title',
        'type',
        'status',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'renewal_date',
        'validity_period',
        'related_project_id',
        'related_tender_id',
        'requirements',
        'attachments',
        'description',
        'notes',
        'is_renewable',
        'renewal_reminder_days'
    ];

    protected $casts = [
        'requirements' => 'array',
        'attachments' => 'array',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'renewal_date' => 'date',
        'is_renewable' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'related_project_id');
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class, 'related_tender_id');
    }

    public function getAttachmentUrlsAttribute()
    {
        if (empty($this->attachments)) {
            return [];
        }

        return array_map(function ($attachment) {
            return [
                'name' => $attachment['name'],
                'path' => asset('storage/' . $attachment['path']),
                'type' => $attachment['type'],
                'size' => $attachment['size']
            ];
        }, $this->attachments);
    }
}
