<?php

namespace App\Http\Controllers;

use App\Models\GlobalSummary;
use App\Models\Application;
use Illuminate\Http\Request;

class GlobalSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $globalSummaries = GlobalSummary::with('application')->latest()->paginate(10);
        return view('admin.global-summaries.index', compact('globalSummaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $applications = Application::all();
        return view('admin.global-summaries.create', compact('applications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'sim_number' => 'nullable|string|max:50',
            'consumer_address' => 'nullable|string',
            'date_on_draft_store' => 'nullable|date',
            'date_received_lm_consumer' => 'nullable|date',
            'customer_mobile_no' => 'nullable|string|max:20',
            'customer_sc_no' => 'nullable|string|max:50',
            'date_return_sdc_billing' => 'nullable|date',
        ]);

        // Get application data
        $application = Application::findOrFail($request->application_id);
        
        // Set values from application
        $validated['application_no'] = $application->application_no;
        $validated['customer_name'] = $application->customer_name;
        $validated['consumer_address'] = $application->address ?? $validated['consumer_address'] ?? null;
        $validated['meter_no'] = $application->meter_number;
        
        $globalSummary = GlobalSummary::create($validated);

        return redirect()->route('admin.global-summaries.index')
                        ->with('success', 'Global summary created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GlobalSummary $globalSummary)
    {
        $globalSummary->load('application');
        return view('admin.global-summaries.show', compact('globalSummary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GlobalSummary $globalSummary)
    {
        $applications = Application::all();
        return view('admin.global-summaries.edit', compact('globalSummary', 'applications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GlobalSummary $globalSummary)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'sim_number' => 'nullable|string|max:50',
            'consumer_address' => 'nullable|string',
            'date_on_draft_store' => 'nullable|date',
            'date_received_lm_consumer' => 'nullable|date',
            'customer_mobile_no' => 'nullable|string|max:20',
            'customer_sc_no' => 'nullable|string|max:50',
            'date_return_sdc_billing' => 'nullable|date',
        ]);

        // Get application data
        $application = Application::findOrFail($request->application_id);
        
        // Set values from application
        $validated['application_no'] = $application->application_no;
        $validated['customer_name'] = $application->customer_name;
        $validated['consumer_address'] = $application->address ?? $validated['consumer_address'] ?? null;
        $validated['meter_no'] = $application->meter_number;
        
        $globalSummary->update($validated);

        return redirect()->route('admin.global-summaries.index')
                        ->with('success', 'Global summary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GlobalSummary $globalSummary)
    {
        $globalSummary->delete();

        return redirect()->route('admin.global-summaries.index')
                        ->with('success', 'Global summary deleted successfully.');
    }
}