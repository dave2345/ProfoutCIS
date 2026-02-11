<?php

namespace App\Livewire\Certificates;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $certificate;
    public $certificateId;
    public $activeTab = 'details';
    public $previewUrl = null;
    public $previewType = null;

    protected $listeners = ['refreshCertificate' => '$refresh'];

    public function mount($id)
    {
        $this->certificateId = $id;
        $this->loadCertificate();
    }

    public function loadCertificate()
    {
        $this->certificate = Certificate::with(['user', 'project', 'tender'])
            ->findOrFail($this->certificateId);
    }

    public function previewAttachment($index)
    {
        if (isset($this->certificate->attachments[$index])) {
            $attachment = $this->certificate->attachments[$index];
            $path = 'storage/' . $attachment['path'];

            if (file_exists(public_path($path))) {
                $this->previewUrl = asset($path);
                $this->previewType = $attachment['type'];
                $this->dispatchBrowserEvent('show-preview-modal');
            }
        }
    }

    public function downloadAttachment($index)
    {
        if (isset($this->certificate->attachments[$index])) {
            $attachment = $this->certificate->attachments[$index];
            $path = 'storage/' . $attachment['path'];

            if (file_exists(public_path($path))) {
                return response()->download(public_path($path), $attachment['name']);
            }
        }

        session()->flash('error', 'File not found.');
    }

    public function render()
    {
        return view('livewire.certificates.show', [
            'cert' => $this->certificate
        ]);
    }
}
