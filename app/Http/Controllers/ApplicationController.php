<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Meter;
use App\Models\Company;
use App\Models\Subdivision;
use Illuminate\Http\Request;
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
            return response()->json(['exists' => false]);
        }
        
        $exists = Meter::where('meter_no', $meterNo)->exists();
        
        return response()->json(['exists' => $exists]);
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
            'phone'          => 'nullable|string|max:30',
            'address'        => 'nullable|string',
            'company_id'     => 'nullable|exists:companies,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'meter_number'   => 'nullable|string|max:100',
            'connection_type'=> 'nullable|string|max:50',
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
        
        // Create application
        $app = Application::create($validated);

        // Create initial history
        ApplicationHistory::create([
            'application_id' => $app->id,
            'subdivision_id' => $app->subdivision_id,
            'company_id' => $app->company_id,
            'action_type' => 'submitted',
            'remarks' => 'Application submitted by customer',
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
}