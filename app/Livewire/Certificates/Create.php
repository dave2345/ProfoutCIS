<?php

namespace App\Livewire\Certificates;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\Tender;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\FileExtractionService;

class Create extends Component
{
    use WithFileUploads;

    public $certificate_number;
    public $title;
    public $type = 'compliance';
    public $status = 'draft';
    public $issuing_authority;
    public $issue_date;
    public $expiry_date;
    public $renewal_date;
    public $validity_period;
    public $related_project_id;
    public $related_tender_id;
    public $description;
    public $notes;
    public $is_renewable = false;
    public $renewal_reminder_days = 30;

    public $files = [];
    public $extractedData = [];
    public $isExtracting = false;
    public $extractionErrors = [];

    public $projects = [];
    public $tenders = [];

    protected $rules = [
        'certificate_number' => 'required|unique:certificates',
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
        'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ];

    public function mount()
    {
        $this->projects = Project::all();
        $this->tenders = Tender::all();
        $this->certificate_number = $this->generateCertificateNumber();
    }

    public function generateCertificateNumber()
    {
        return 'CERT-' . date('Ymd') . '-' . Str::random(6);
    }

    public function extractData()
    {
        $this->validate([
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $this->isExtracting = true;
        $this->extractedData = [];
        $this->extractionErrors = [];

        $extractionService = new FileExtractionService();

        foreach ($this->files as $file) {
            try {
                $filename = $file->getClientOriginalName();

                // Determine file type and extract accordingly
                $mimeType = $file->getMimeType();
                $extracted = [];

                if (str_contains($mimeType, 'pdf')) {
                    $extracted = $extractionService->extractFromPdf($file);
                } elseif (str_contains($mimeType, 'image')) {
                    $extracted = $extractionService->extractFromImage($file);
                }

                // Fallback to filename extraction if OCR/PDF parsing fails
                if (empty($extracted)) {
                    $extracted = [
                        'title' => $this->extractTitleFromFilename($filename),
                        'certificate_number' => $this->extractNumberFromFilename($filename),
                        'issuing_authority' => $this->extractAuthorityFromFilename($filename),
                    ];
                }

                $this->extractedData[] = array_merge([
                    'filename' => $filename,
                    'file_type' => $mimeType,
                    'extraction_method' => empty($extracted) ? 'filename' : (str_contains($mimeType, 'pdf') ? 'pdf_parser' : 'ocr'),
                    'success' => !empty($extracted)
                ], $extracted);
            } catch (\Exception $e) {
                $this->extractionErrors[] = [
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];

                // Fallback extraction from filename
                $this->extractedData[] = [
                    'filename' => $filename,
                    'title' => $this->extractTitleFromFilename($filename),
                    'certificate_number' => $this->extractNumberFromFilename($filename),
                    'issuing_authority' => $this->extractAuthorityFromFilename($filename),
                    'file_type' => $mimeType,
                    'extraction_method' => 'filename_fallback',
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        // Auto-fill form with extracted data from successful extractions
        $successfulExtractions = array_filter($this->extractedData, function ($item) {
            return $item['success'] ?? false;
        });

        if (!empty($successfulExtractions)) {
            $firstData = reset($successfulExtractions);

            // Use extracted data or fallback to current values
            $this->title = $firstData['title'] ?? $this->title;
            $this->certificate_number = $firstData['certificate_number'] ?? $this->certificate_number;
            $this->issuing_authority = $firstData['issuing_authority'] ?? $this->issuing_authority;

            // Parse dates if available
            if (isset($firstData['issue_date'])) {
                $this->issue_date = $this->parseExtractedDate($firstData['issue_date']);
            }
            if (isset($firstData['expiry_date'])) {
                $this->expiry_date = $this->parseExtractedDate($firstData['expiry_date']);
            }
            if (isset($firstData['validity_period'])) {
                $this->validity_period = $firstData['validity_period'];
            }
        }

        $this->isExtracting = false;

        // Dispatch browser event (Livewire 3 syntax)
        $this->dispatch('extraction-complete', [
            'successful' => count($successfulExtractions),
            'total' => count($this->files)
        ]);

        // Also dispatch a Livewire event for any listeners
        $this->dispatch('extractionCompleted', count($successfulExtractions));
    }

    private function parseExtractedDate($dateString)
    {
        try {
            // Try various date formats
            $formats = [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'd-m-Y',
                'm-d-Y',
                'Y/m/d',
                'Y-m-d H:i:s',
                'd F Y',
                'F d, Y',
            ];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }

            // Try strtotime as fallback
            $timestamp = strtotime($dateString);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
        } catch (\Exception $e) {
            // If date parsing fails, return null
        }

        return null;
    }

    private function extractTitleFromFilename($filename)
    {
        // Remove extension and common prefixes/suffixes
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[\d\-_]+/', ' ', $name); // Remove numbers, dashes, underscores
        $name = preg_replace('/certificate|cert|license|award|training|compliance/i', '', $name); // Remove common words
        $name = trim($name);

        if (empty($name)) {
            $name = 'Certificate ' . date('Ymd');
        }

        return ucwords(strtolower($name));
    }

    private function extractNumberFromFilename($filename)
    {
        // Look for certificate number patterns
        $patterns = [
            '/cert[\.\s]*no[\.\s]*[:#]?\s*([A-Z0-9\-]+)/i',
            '/certificate[\.\s]*no[\.\s]*[:#]?\s*([A-Z0-9\-]+)/i',
            '/([A-Z]{2,}\d{4,}-\d{4,})/',
            '/(\d{4}[\/\-]\d{4}[\/\-]\d{4})/',
            '/(CERT[\-\s]\d+)/i',
            '/(\d{10,})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $filename, $matches)) {
                return strtoupper(trim($matches[1]));
            }
        }

        // Extract numbers from filename as fallback
        if (preg_match('/\d+/', $filename, $matches)) {
            return 'CERT-' . $matches[0];
        }

        return $this->generateCertificateNumber();
    }

    private function extractAuthorityFromFilename($filename)
    {
        // Look for common issuing authorities in filename
        $authorities = [
            'ISO' => 'International Organization for Standardization',
            'ANSI' => 'American National Standards Institute',
            'AWS' => 'Amazon Web Services',
            'Microsoft' => 'Microsoft Corporation',
            'Google' => 'Google LLC',
            'Cisco' => 'Cisco Systems',
            'PMI' => 'Project Management Institute',
            'IEEE' => 'Institute of Electrical and Electronics Engineers',
            'NIST' => 'National Institute of Standards and Technology',
            'UL' => 'Underwriters Laboratories',
            'FDA' => 'Food and Drug Administration',
            'EPA' => 'Environmental Protection Agency',
            'OSHA' => 'Occupational Safety and Health Administration',
        ];

        $filenameUpper = strtoupper($filename);

        foreach ($authorities as $key => $value) {
            if (strpos($filenameUpper, strtoupper($key)) !== false) {
                return $value;
            }
        }

        // Extract company/organization name from filename
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[\d\-_]+/', ' ', $name);
        $name = preg_replace('/cert.*|lic.*|awd.*|train.*/i', '', $name);
        $name = trim($name);

        if (str_word_count($name) > 2) {
            // Take first few words as organization name
            $words = explode(' ', $name);
            return ucwords(implode(' ', array_slice($words, 0, 3)));
        }

        return 'Issuing Authority';
    }

    public function save()
    {
        $this->validate();

        try {
            $attachments = [];

            // Save uploaded files
            foreach ($this->files as $file) {
                $path = $file->store('certificates/' . date('Y/m'), 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'extraction_data' => $this->getExtractionDataForFile($file->getClientOriginalName())
                ];
            }

            $certificate = Certificate::create([
                'user_id' => Auth::id(),
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
                'requirements' => $this->extractRequirementsFromData(),
            ]);

            session()->flash('message', 'Certificate created successfully.');
            return redirect()->route('certificates.show', $certificate);
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating certificate: ' . $e->getMessage());
        }
    }

    private function getExtractionDataForFile($filename)
    {
        foreach ($this->extractedData as $data) {
            if ($data['filename'] === $filename) {
                return [
                    'method' => $data['extraction_method'] ?? 'unknown',
                    'success' => $data['success'] ?? false,
                    'extracted_at' => now()->toISOString(),
                    'raw_data' => $data
                ];
            }
        }
        return null;
    }

    private function extractRequirementsFromData()
    {
        $requirements = [];

        foreach ($this->extractedData as $data) {
            if (isset($data['requirements']) && is_array($data['requirements'])) {
                $requirements = array_merge($requirements, $data['requirements']);
            }
        }

        return !empty($requirements) ? $requirements : null;
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }

    public function clearExtractedData()
    {
        $this->extractedData = [];
        $this->extractionErrors = [];
    }

    public function render()
    {
        return view('livewire.certificates.create', [
            'extractionSummary' => [
                'total' => count($this->files),
                'successful' => count(array_filter($this->extractedData, function ($item) {
                    return $item['success'] ?? false;
                })),
                'errors' => count($this->extractionErrors),
            ]
        ]);
    }
}
