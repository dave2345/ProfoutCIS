<?php

namespace App\Livewire\Certificates;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\Tender;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $certificate;
    public $certificateId;

    public $certificate_number;
    public $title;
    public $type;
    public $status;
    public $issuing_authority;
    public $issue_date;
    public $expiry_date;
    public $renewal_date;
    public $validity_period;
    public $related_project_id;
    public $related_tender_id;
    public $description;
    public $notes;
    public $is_renewable;
    public $renewal_reminder_days;

    public $newFiles = [];
    public $existingAttachments = [];

    public $projects = [];
    public $tenders = [];

    protected $rules = [
        'certificate_number' => 'required|unique:certificates,certificate_number,' . ',id',
        'title' => 'required|string|max:255',
        'type' => 'required|in:compliance,accreditation,license,award,training,membership,other',
        'status' => 'required|in:draft,active,expired,revoked,renewed',
        'issuing_authority' => 'required|string|max:255',
        'issue_date' => 'required|date',
        'expiry_date' => 'nullable|date',
        'renewal_date' => 'nullable|date',
        'validity_period' => 'nullable|string',
        'related_project_id' => 'nullable|exists:projects,id',
        'related_tender_id' => 'nullable|exists:tenders,id',
        'description' => 'nullable|string',
        'notes' => 'nullable|string',
        'is_renewable' => 'boolean',
        'renewal_reminder_days' => 'integer|min:0',
        'newFiles.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ];

    public function mount($id)
    {
        $this->certificateId = $id;
        $this->certificate = Certificate::findOrFail($id);

        $this->projects = Project::all();
        $this->tenders = Tender::all();

        // Load existing data
        $this->loadCertificateData();
    }

    public function loadCertificateData()
    {
        $this->certificate_number = $this->certificate->certificate_number;
        $this->title = $this->certificate->title;
        $this->type = $this->certificate->type;
        $this->status = $this->certificate->status;
        $this->issuing_authority = $this->certificate->issuing_authority;
        $this->issue_date = $this->certificate->issue_date->format('Y-m-d');
        $this->expiry_date = $this->certificate->expiry_date?->format('Y-m-d');
        $this->renewal_date = $this->certificate->renewal_date?->format('Y-m-d');
        $this->validity_period = $this->certificate->validity_period;
        $this->related_project_id = $this->certificate->related_project_id;
        $this->related_tender_id = $this->certificate->related_tender_id;
        $this->description = $this->certificate->description;
        $this->notes = $this->certificate->notes;
        $this->is_renewable = $this->certificate->is_renewable;
        $this->renewal_reminder_days = $this->certificate->renewal_reminder_days;
        $this->existingAttachments = $this->certificate->attachments ?? [];
    }

    public function update()
    {
        $this->validate();

        try {
            $attachments = $this->existingAttachments;

            // Add new files
            foreach ($this->newFiles as $file) {
                $path = $file->store('certificates/' . date('Y/m'), 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize()
                ];
            }

            $this->certificate->update([
                'certificate_number' => $this->certificate_number,
                'title' => $this->title,
                'type' => $this->type,
                'status' => $this->status,
                'issuing_authority' => $this->issuing_authority,
                'issue_date' => $this->issue_date,
                'expiry_date' => $this->expiry_date,
                'renewal_date' => $this->renewal_date,
                'validity_period' => $this->validity_period,
                'related_project_id' => $this->related_project_id,
                'related_tender_id' => $this->related_tender_id,
                'description' => $this->description,
                'notes' => $this->notes,
                'is_renewable' => $this->is_renewable,
                'renewal_reminder_days' => $this->renewal_reminder_days,
                'attachments' => $attachments,
            ]);

            session()->flash('message', 'Certificate updated successfully.');
            $this->emit('refreshCertificate');

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating certificate: ' . $e->getMessage());
        }
    }

    public function removeExistingAttachment($index)
    {
        if (isset($this->existingAttachments[$index])) {
            $attachment = $this->existingAttachments[$index];

            // Delete file from storage
            Storage::disk('public')->delete($attachment['path']);

            unset($this->existingAttachments[$index]);
            $this->existingAttachments = array_values($this->existingAttachments);
        }
    }

    public function removeNewFile($index)
    {
        if (isset($this->newFiles[$index])) {
            unset($this->newFiles[$index]);
            $this->newFiles = array_values($this->newFiles);
        }
    }

    public function render()
    {
        return view('livewire.certificates.edit');
    }
}
