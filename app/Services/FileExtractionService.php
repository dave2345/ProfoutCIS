<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;

use Illuminate\Support\Str;
use Nette\Utils\Image;

class FileExtractionService
{
    protected $parser;
    protected $tesseractPath;
    protected $customPatterns;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->tesseractPath = config('app.tesseract_path', '/usr/bin/tesseract');

        // Custom patterns for different certificate types
        $this->customPatterns = [
            'compliance' => [
                'certificate_number' => '/(?:Certificate\s*(?:No|Number|#)\s*[:]?\s*)([A-Z0-9\-]+)/i',
                'standard' => '/(?:Standard|Compliance)\s*[:]?\s*(.+)/i',
                'valid_until' => '/(?:Valid\s*(?:until|through|to)|Expiry\s*Date)\s*[:]?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i',
            ],
            'training' => [
                'certificate_number' => '/(?:Cert(?:ificate)?\s*(?:ID|No|#)\s*[:]?\s*)([A-Z0-9\-]+)/i',
                'course' => '/(?:Course|Training)\s*(?:Title|Name)?\s*[:]?\s*(.+)/i',
                'completion_date' => '/(?:Date\s*of\s*Completion|Issued\s*on)\s*[:]?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i',
            ],
            'license' => [
                'license_number' => '/(?:License\s*(?:No|Number|#)\s*[:]?\s*)([A-Z0-9\-]+)/i',
                'type' => '/(?:License\s*Type|Category)\s*[:]?\s*(.+)/i',
                'expiration' => '/(?:Expires|Valid\s*until|Renewal\s*Date)\s*[:]?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i',
            ],
        ];
    }

    public function extractFromPdf($file)
    {
        try {
            $pdf = $this->parser->parseFile($file->path());
            $text = $pdf->getText();
            $details = $pdf->getDetails();

            $extractedData = $this->parseCertificateData($text);

            // Extract metadata if available
            if (isset($details['Title'])) {
                $extractedData['title'] = $details['Title'];
            }
            if (isset($details['Author'])) {
                $extractedData['issuing_authority'] = $details['Author'];
            }
            if (isset($details['CreationDate'])) {
                $extractedData['issue_date'] = $this->parsePdfDate($details['CreationDate']);
            }
            dd($extractedData);
            return $extractedData;

        } catch (\Exception $e) {
            Log::error('PDF extraction failed: ' . $e->getMessage());
            throw new \Exception('PDF parsing failed: ' . $e->getMessage());
        }
    }

    public function extractFromImage($file)
    {
        try {
            // Preprocess image for better OCR results
            $preprocessedPath = $this->preprocessImage($file);

            $ocr = new TesseractOCR($preprocessedPath);
            $ocr->executable($this->tesseractPath);

            // Configure OCR for better text recognition
            $ocr->psm(6) // Assume a single uniform block of text
                ->oem(3) // Default OCR engine mode
                ->lang('eng'); // English language

            $text = $ocr->run();

            // Clean up temporary file
            unlink($preprocessedPath);

            return $this->parseCertificateData($text);

        } catch (\Exception $e) {
            Log::error('Image OCR extraction failed: ' . $e->getMessage());

            // Try with basic OCR settings if advanced fails
            try {
                $ocr = new TesseractOCR($file->path());
                $text = $ocr->run();
                return $this->parseCertificateData($text);
            } catch (\Exception $e2) {
                throw new \Exception('OCR failed: ' . $e->getMessage());
            }
        }
    }

    private function preprocessImage($file)
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'ocr_') . '.png';

        $image = Image::make($file->path());

        // Apply preprocessing for better OCR
        $image->greyscale() // Convert to grayscale
              ->contrast(20) // Increase contrast
              ->brightness(10) // Adjust brightness
              ->sharpen(5) // Sharpen image
              ->save($tempPath);

        return $tempPath;
    }

    private function parseCertificateData($text)
    {
        $text = $this->cleanText($text);

        // Try to detect certificate type first
        $detectedType = $this->detectCertificateType($text);

        // Use custom patterns for detected type or general patterns
        $patterns = $detectedType ?
            array_merge($this->getGeneralPatterns(), $this->customPatterns[$detectedType]) :
            $this->getGeneralPatterns();

        $data = [];

        foreach ($patterns as $key => $pattern) {
            if (is_array($pattern)) {
                foreach ($pattern as $subPattern) {
                    if ($value = $this->extractPattern($text, $subPattern)) {
                        $data[$key] = $value;
                        break;
                    }
                }
            } else {
                if ($value = $this->extractPattern($text, $pattern)) {
                    $data[$key] = $value;
                }
            }
        }

        // Additional processing for specific fields
        if (empty($data['title']) && !empty($data['certificate_number'])) {
            $data['title'] = 'Certificate ' . $data['certificate_number'];
        }

        if (!empty($data['issue_date']) && !empty($data['expiry_date'])) {
            $data['validity_period'] = $this->calculateValidityPeriod(
                $data['issue_date'],
                $data['expiry_date']
            );
        }

        // Extract requirements/conditions
        $data['requirements'] = $this->extractRequirements($text);

        return $data;
    }

    private function getGeneralPatterns()
    {
        return [
            'certificate_number' => [
                '/(?:Certificate|Cert|License)\s*(?:No\.?|Number|#|ID)\s*[:]?\s*([A-Z0-9\-]+)/i',
                '/(?:Ref\.|Reference)\s*[:]?\s*([A-Z0-9\-]+)/i',
                '/([A-Z]{2,}\d{4,}[A-Z0-9\-]*)/',
            ],
            'title' => [
                '/(?:Certificate\s*of|This\s*is\s*to\s*certify\s*that|Certificate|Title)\s*[:]?\s*(.+?)(?=\n|$)/i',
                '/(?:in\s*recognition\s*of|for\s*completion\s*of)\s*(.+?)(?=\n|$)/i',
            ],
            'issuing_authority' => [
                '/(?:Issued\s*by|Authority|Organization|Company)\s*[:]?\s*(.+?)(?=\n|$)/i',
                '/(?:Signed\s*by|Approved\s*by)\s*[:]?\s*(.+?)(?=\n|$)/i',
                '/(?:This\s*certificate\s*is\s*issued\s*by)\s*(.+?)(?=\n|$)/i',
            ],
            'issue_date' => [
                '/(?:Date\s*of\s*Issue|Issued\s*on|Issuance\s*Date|Date)\s*[:]?\s*(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                '/(?:Dated)\s*[:]?\s*(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                '/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})(?=\s*(?:Issue|Date|Issued))/i',
            ],
            'expiry_date' => [
                '/(?:Valid\s*until|Expiry\s*Date|Expires\s*on|Valid\s*through)\s*[:]?\s*(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
                '/(?:Renewal\s*Date|Next\s*Review)\s*[:]?\s*(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{2,4})/i',
            ],
            'recipient' => [
                '/(?:Awarded\s*to|Presented\s*to|This\s*certificate\s*is\s*awarded\s*to)\s*(.+?)(?=\n|$)/i',
                '/(?:Name\s*of\s*Recipient|Recipient)\s*[:]?\s*(.+?)(?=\n|$)/i',
            ],
        ];
    }

    private function detectCertificateType($text)
    {
        $text = strtolower($text);

        if (strpos($text, 'compliance') !== false ||
            strpos($text, 'standard') !== false ||
            strpos($text, 'iso') !== false) {
            return 'compliance';
        }

        if (strpos($text, 'training') !== false ||
            strpos($text, 'course') !== false ||
            strpos($text, 'completion') !== false) {
            return 'training';
        }

        if (strpos($text, 'license') !== false ||
            strpos($text, 'permit') !== false ||
            strpos($text, 'authorization') !== false) {
            return 'license';
        }

        if (strpos($text, 'award') !== false ||
            strpos($text, 'achievement') !== false ||
            strpos($text, 'recognition') !== false) {
            return 'award';
        }

        return null;
    }

    private function cleanText($text)
    {
        // Replace multiple spaces and newlines with single space
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove special characters but keep basic punctuation
        $text = preg_replace('/[^\w\s\.\,\-\:\/\#\(\)]/', '', $text);

        return trim($text);
    }

    private function extractPattern($text, $pattern)
    {
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function parsePdfDate($pdfDate)
    {
        // PDF dates are in format: D:YYYYMMDDHHMMSS
        if (preg_match('/D:(\d{4})(\d{2})(\d{2})/', $pdfDate, $matches)) {
            return "{$matches[1]}-{$matches[2]}-{$matches[3]}";
        }

        return null;
    }

    private function calculateValidityPeriod($issueDate, $expiryDate)
    {
        try {
            $issue = new \DateTime($issueDate);
            $expiry = new \DateTime($expiryDate);
            $interval = $issue->diff($expiry);

            $years = $interval->y;
            $months = $interval->m;
            $days = $interval->d;

            $parts = [];
            if ($years > 0) $parts[] = $years . ' year' . ($years > 1 ? 's' : '');
            if ($months > 0) $parts[] = $months . ' month' . ($months > 1 ? 's' : '');
            if ($days > 0 && $years == 0) $parts[] = $days . ' day' . ($days > 1 ? 's' : '');

            return implode(', ', $parts);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extractRequirements($text)
    {
        $requirements = [];

        // Look for requirement patterns
        $requirementPatterns = [
            '/(?:must|shall|required to|requirement)[^\.]+\./i',
            '/(?:condition|criteria|standard)[^\.]+\./i',
            '/(?:compliance with|in accordance with)[^\.]+\./i',
        ];

        foreach ($requirementPatterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[0] as $match) {
                    $requirements[] = trim($match);
                }
            }
        }

        return !empty($requirements) ? array_slice($requirements, 0, 10) : []; // Limit to 10 requirements
    }

    /**
     * Extract text from multiple files and merge results
     */
    public function extractFromMultipleFiles($files)
    {
        $allData = [];

        foreach ($files as $file) {
            try {
                $mimeType = $file->getMimeType();

                if (str_contains($mimeType, 'pdf')) {
                    $data = $this->extractFromPdf($file);
                } elseif (str_contains($mimeType, 'image')) {
                    $data = $this->extractFromImage($file);
                } else {
                    continue;
                }

                $data['source_file'] = $file->getClientOriginalName();
                $allData[] = $data;

            } catch (\Exception $e) {
                Log::warning('Failed to extract from file: ' . $file->getClientOriginalName(), [
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $this->mergeExtractedData($allData);
    }

    private function mergeExtractedData($allData)
    {
        if (empty($allData)) {
            return [];
        }

        $merged = [];

        // Get all unique keys
        $keys = [];
        foreach ($allData as $data) {
            $keys = array_merge($keys, array_keys($data));
        }
        $keys = array_unique($keys);

        // Merge data, giving priority to non-empty values
        foreach ($keys as $key) {
            foreach ($allData as $data) {
                if (!empty($data[$key]) && empty($merged[$key])) {
                    $merged[$key] = $data[$key];
                    break;
                }
            }
        }

        return $merged;
    }
}
