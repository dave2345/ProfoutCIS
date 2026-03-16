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
    public function exportReport(HttpRequest $request)
    {
        // $reportData = decrypt($request->data);

        // if ($request->format === 'pdf') {
        //     $pdf = pdf::loadView('exports.finance-report-pdf', ['data' => $reportData]);
        //     return $pdf->download('finance-report-' . now()->format('Y-m-d') . '.pdf');
        // }

        // if ($request->format === 'excel') {
        //     // Implement Excel export logic here
        //     // You can use Laravel Excel package
        // }

        // return redirect()->back();
    }

    public function showRequest($id)
    {
        $request = Request::with('user')->findOrFail($id);
        return view('finance.show-request', compact('request'));
    }
}
