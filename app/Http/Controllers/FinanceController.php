<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;

class FinanceController extends Controller
{
    public function index()
    {
        $requests = Request::where('status', 'payment_processing')->get();
        return view('finance.index', compact('requests'));
    }
    public function show($id)
    {
        // Show specific financial record
    }
    public function create()
    {
        // Show form to create new financial record
    }
    public function store(Request $request)
    {
        // Validate and save new financial record
    }
}
