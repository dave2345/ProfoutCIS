<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Project;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $certificateId = Certificate::all()->first()?->id;

        return view('certificates.index', compact('certificateId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        return view('certificates.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        // Check authorization
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {

    }

    /**
     * Renew a certificate.
     */



}
