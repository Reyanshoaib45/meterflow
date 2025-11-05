<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Meter;
use App\Models\Company;
use App\Models\Subdivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Show application form.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('user-form', compact('companies', 'subdivisions'));
    }

    /**
     * Check if meter number exists (AJAX).
     */
    public function checkMeter(Request $request)
    {
        $meterNo = $request->input('meter_no');
        
        if (empty($meterNo)) {
            return response()->json(['available' => false, 'message' => 'Enter meter number']);
        }
        
        $meter = Meter::where('meter_no', $meterNo)->first();
        
        if ($meter) {
            return response()->json([
                'available' => true, 
                'exists' => true,
                'message' => 'Meter number is available',
                'meter' => [
                    'consumer' => $meter->consumer->name ?? 'N/A',
                    'status' => $meter->status
                ]
            ]);
        }
        
        return response()->json([
            'available' => false,
            'exists' => false, 
            'message' => 'Meter number not found in system'
        ]);
    }
    
    /**
     * Check if application number is valid (AJAX).
     */
    public function checkApplicationNumber(Request $request)
    {
        $applicationNo = $request->input('application_no');
        
        if (empty($applicationNo)) {
            return response()->json(['available' => true, 'message' => '']);
        }
        
        $application = Application::where('application_no', $applicationNo)->first();
        
        // If no application exists, it's available (new application number)
        if (!$application) {
            return response()->json([
                'available' => true,
                'message' => 'Application number is available'
            ]);
        }
        
        // If application already exists, it's not available for new submission
        return response()->json([
            'available' => false,
            'message' => 'This application number already exists. Please use a different number.'
        ]);
    }
    public function track(Request $request)
    {
        $application = null;
        
        if ($request->has('application_no')) {
            $application = Application::with(['company', 'subdivision', 'histories'])
                ->where('application_no', $request->application_no)
                ->first();
        }
        
        return view('user.track', compact('application'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_no' => 'required|string|max:50|unique:applications,application_no',
            'customer_name'  => 'required|string|max:200',
            'customer_cnic'  => 'nullable|string|max:30',
            'phone'          => 'required|string|regex:/^[0-9]{12}$/|max:12',
            'address'        => 'nullable|string',
            'company_id'     => 'nullable|exists:companies,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'meter_number'   => 'nullable|string|max:100',
            'connection_type'=> 'nullable|string|max:50',
        ], [
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be exactly 12 numeric digits.',
            'phone.max' => 'The phone number must be exactly 12 digits.',
        ]);

        // Check if meter number already exists
        if (!empty($validated['meter_number'])) {
            $meterExists = Meter::where('meter_no', $validated['meter_number'])->exists();
            if ($meterExists) {
                return back()->withErrors([
                    'meter_number' => 'This meter number is already registered in the system.'
                ])->withInput();
            }
        }

        // Set default status
        $validated['status'] = 'pending';
        $validated['cnic'] = $validated['customer_cnic'] ?? null;
        unset($validated['customer_cnic']);
        
        // Set user_id if user is authenticated
        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }
        
        // Create application
        $app = Application::create($validated);

        // Create initial history
        ApplicationHistory::create([
            'application_id' => $app->id,
            'subdivision_id' => $app->subdivision_id,
            'company_id' => $app->company_id,
            'action_type' => 'submitted',
            'remarks' => 'Application submitted by customer',
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        return redirect()->route('application.thanks', ['application_no' => $app->application_no]);
    }

    /**
     * Show thank you page after submission.
     */
    public function thanks($application_no)
    {
        $application = Application::where('application_no', $application_no)->firstOrFail();
        
        return view('user.thanks', compact('application'));
    }

    /**
     * Close application (user can close their own application).
     */
    public function close(Request $request, $application_no)
    {
        $application = Application::where('application_no', $application_no)->firstOrFail();
        
        // Only allow closing if status is pending
        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be closed.');
        }
        
        $application->update(['status' => 'closed']);
        
        // Add to history
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'closed',
            'remarks' => 'Application closed by customer',
        ]);
        
        return redirect()->route('track')->with('success', 'Application has been closed successfully.');
    }

    /**
     * Generate invoice for approved application with fee.
     */
    public function generateInvoice($application_no)
    {
        $application = Application::where('application_no', $application_no)
            ->with(['company', 'subdivision'])
            ->firstOrFail();
        
        // Check if application is approved and has fee
        if ($application->status !== 'approved' || empty($application->fee_amount)) {
            return back()->with('error', 'Invoice can only be generated for approved applications with fee.');
        }
        
        return view('user.invoice', compact('application'));
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadInvoice($application_no)
    {
        $application = Application::where('application_no', $application_no)
            ->with(['company', 'subdivision'])
            ->firstOrFail();
        
        // Check if application is approved and has fee
        if ($application->status !== 'approved' || empty($application->fee_amount)) {
            return back()->with('error', 'Invoice can only be downloaded for approved applications with fee.');
        }
        
        // Generate PDF using browser print or return HTML for download
        // For now, we'll return a view optimized for PDF generation
        $pdfView = view('user.invoice-pdf', compact('application'));
        
        // Generate filename
        $filename = 'Invoice_' . $application->application_no . '_' . date('Y-m-d') . '.html';
        
        // Return as downloadable HTML (can be converted to PDF by browser)
        return response()->make($pdfView, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}