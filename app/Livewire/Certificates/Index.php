<?php

namespace App\Livewire\Certificates;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $status = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'type', 'status', 'perPage'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function downloadAttachment($certificateId, $attachmentIndex)
    {
        $certificate = Certificate::findOrFail($certificateId);

        if (isset($certificate->attachments[$attachmentIndex])) {
            $attachment = $certificate->attachments[$attachmentIndex];
            $path = 'storage/' . $attachment['path'];

            if (file_exists(public_path($path))) {
                return response()->download(public_path($path), $attachment['name']);
            }
        }

        session()->flash('error', 'File not found.');
    }

    public function deleteCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();

        session()->flash('message', 'Certificate deleted successfully.');
    }

    public function render()
    {
        $query = Certificate::with(['user', 'project', 'tender'])
            ->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('certificate_number', 'like', '%' . $this->search . '%')
                  ->orWhere('issuing_authority', 'like', '%' . $this->search . '%');
            });

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $certificates = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.certificates.index', [
            'certificates' => $certificates,
            'types' => ['compliance', 'accreditation', 'license', 'award', 'training', 'membership', 'other'],
            'statuses' => ['draft', 'active', 'expired', 'revoked', 'renewed']
        ]);
    }
}
