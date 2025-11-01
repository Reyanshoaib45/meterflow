<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\Consumer;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\ExtraSummary;
use App\Models\Bill;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MeterController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of meters.
     */
    public function index(Request $request)
    {
        $query = Meter::with(['consumer', 'subdivision']);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('meter_no', 'like', "%{$search}%")
                  ->orWhereHas('consumer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        // Use 27 records for initial load, 15 for subsequent pages
        $perPage = $request->get('page', 1) == 1 ? 27 : 15;
        $meters = $query->latest()->paginate($perPage);
        $subdivisions = Subdivision::orderBy('name')->get();

        return view('admin.meters.index', compact('meters', 'subdivisions'));
    }

    /**
     * Show the form for creating a new meter.
     */
    public function create()
    {
        $consumers = Consumer::where('status', 'active')->orderBy('name')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.meters.create', compact('consumers', 'subdivisions'));
    }

    /**
     * Store a newly created meter.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meter_no' => 'required|string|unique:meters',
            'meter_make' => 'nullable|string',
            'consumer_id' => 'nullable|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'installed_on' => 'nullable|date',
            'remarks' => 'nullable|string',
            'meter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('meter_image')) {
            $imagePath = $request->file('meter_image')->store('meter_images', 'public');
            $validated['meter_image'] = $imagePath;
        }

        // Set in_store to true by default
        $validated['in_store'] = true;
        
        $meter = Meter::create($validated);

        self::logActivity('Meters', 'Created', 'Meter', $meter->id, null, $validated);

        // Redirect to assignment page instead of index
        return redirect()->route('admin.meters.assign', $meter->id)
            ->with('success', 'Meter created successfully. Please assign it to a consumer.');
    }

    /**
     * Show meter assignment page.
     */
    public function assign(Meter $meter)
    {
        $meter->load(['consumer', 'subdivision', 'application']);
        $consumers = Consumer::where('subdivision_id', $meter->subdivision_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get applications for this subdivision
        // Show all approved applications in this subdivision (even if they have meters, for reassignment)
        $applications = Application::where('status', 'approved')
            ->orderBy('application_no')
            ->get();
        
        return view('admin.meters.assign', compact('meter', 'consumers', 'applications'));
    }

    /**
     * Get application details via AJAX.
     */
    public function getApplicationDetails(Request $request, Meter $meter)
    {
        $applicationId = $request->input('application_id');
        $applicationNo = $request->input('application_no'); // Also support search by number
        
        if (!$applicationId && !$applicationNo) {
            return response()->json([
                'success' => false,
                'message' => 'Application ID or Application Number is required'
            ]);
        }
        
        // Find by ID or application number
        if ($applicationNo) {
            $application = Application::with('subdivision')
                ->where('application_no', $applicationNo)
                ->first();
        } else {
            $application = Application::with('subdivision')->find($applicationId);
        }
        
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ]);
        }
        
        // Check if application is in same subdivision as meter
        if ($application->subdivision_id != $meter->subdivision_id) {
            return response()->json([
                'success' => false,
                'message' => 'This application belongs to a different subdivision. Please select a meter from the same subdivision.',
                'application_subdivision' => $application->subdivision->name ?? 'N/A',
                'meter_subdivision' => $meter->subdivision->name ?? 'N/A'
            ]);
        }
        
        // Check if application is approved
        if ($application->status != 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'This application is not approved yet. Status: ' . ucfirst($application->status),
                'application_status' => $application->status
            ]);
        }
        
        // Find consumer by CNIC
        $consumer = Consumer::where('cnic', $application->cnic)->first();
        
        return response()->json([
            'success' => true,
            'application' => [
                'id' => $application->id,
                'application_no' => $application->application_no,
                'customer_name' => $application->customer_name,
                'cnic' => $application->cnic,
                'phone' => $application->phone,
                'address' => $application->address,
            ],
            'consumer' => $consumer ? [
                'id' => $consumer->id,
                'name' => $consumer->name,
                'consumer_id' => $consumer->consumer_id,
                'cnic' => $consumer->cnic,
            ] : null
        ]);
    }

    /**
     * Store meter assignment.
     */
    public function storeAssignment(Request $request, Meter $meter)
    {
        $validated = $request->validate([
            'consumer_id' => 'nullable|exists:consumers,id',
            'application_id' => 'required|exists:applications,id',
            'add_to_quick_summary' => 'nullable|boolean',
        ]);
        
        $oldValues = $meter->toArray();
        
        // Update meter with application (required)
        $application = Application::find($validated['application_id']);
        $meter->update([
            'application_id' => $validated['application_id'],
            'consumer_id' => $validated['consumer_id'] ?? Consumer::where('cnic', $application->cnic)->first()?->id ?? $meter->consumer_id,
        ]);
        
        // Always mark as cut from store when assigning
        $meter->update(['in_store' => false]);
        
        // Refresh application after update
        $application = $application->fresh();
        
        // 1. Create Application History for User (visible in user tracking)
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'user_id' => Auth::id(),
            'action_type' => 'meter_assigned',
            'remarks' => "Meter {$meter->meter_no} assigned to application {$application->application_no}",
            'meter_number' => $meter->meter_no,
            'sent_to_ro' => false, // Will be sent to RO after invoice creation
        ]);
        
        // 2. Create Invoice/Bill for SDO (LS) - Initial bill entry
        // Get consumer for the application
        $consumer = $meter->consumer ?? Consumer::where('cnic', $application->cnic)->first();
        
        if ($consumer) {
            // Create initial/draft bill that SDO can view and manage
            // This bill will be visible to LS/SDO for management
            $billNumber = 'BILL-' . date('Ym') . '-' . str_pad(Bill::count() + 1, 6, '0', STR_PAD_LEFT);
            
            $bill = Bill::create([
                'bill_number' => $billNumber,
                'consumer_id' => $consumer->id,
                'meter_id' => $meter->id,
                'subdivision_id' => $meter->subdivision_id,
                'billing_month' => date('F'),
                'billing_year' => date('Y'),
                'previous_reading' => $meter->reading ?? 0,
                'current_reading' => $meter->reading ?? 0,
                'units_consumed' => 0,
                'rate_per_unit' => 0,
                'energy_charges' => 0,
                'fixed_charges' => 0,
                'gst_amount' => 0,
                'tv_fee' => 0,
                'meter_rent' => 0,
                'total_amount' => 0,
                'payment_status' => 'pending',
                'due_date' => now()->addDays(30),
                'issue_date' => now(),
                'remarks' => "Initial bill created when meter {$meter->meter_no} was assigned to application {$application->application_no}",
            ]);
            
            // 3. Create Application History entry to send to RO
            ApplicationHistory::create([
                'application_id' => $application->id,
                'subdivision_id' => $application->subdivision_id,
                'company_id' => $application->company_id,
                'user_id' => Auth::id(),
                'action_type' => 'invoice_created',
                'remarks' => "Invoice/Bill {$billNumber} created for meter {$meter->meter_no}. Sent to RO for billing management.",
                'meter_number' => $meter->meter_no,
                'seo_number' => 'SEO-' . date('Ym') . '-' . str_pad($application->id, 4, '0', STR_PAD_LEFT),
                'sent_to_ro' => true, // Send this to RO
            ]);
        }
        
        // Add to quick summary (ExtraSummary) if requested
        if ($request->has('add_to_quick_summary')) {
            ExtraSummary::create([
                'company_id' => $application->company_id,
                'subdivision_id' => $meter->subdivision_id,
                'application_id' => $application->id,
                'application_no' => $application->application_no,
                'customer_name' => $application->customer_name,
                'meter_no' => $meter->meter_no,
                'sim_date' => now(),
            ]);
        }
        
        self::logActivity('Meters', 'Assigned', 'Meter', $meter->id, $oldValues, $meter->fresh()->toArray());
        
        return redirect()->route('admin.meters.index')
            ->with('success', 'Meter assigned successfully. Application history created, invoice sent to SDO and RO.');
    }

    /**
     * Display the specified meter.
     */
    public function show(Meter $meter)
    {
        $meter->load(['consumer', 'subdivision', 'bills', 'application']);
        
        return view('admin.meters.show', compact('meter'));
    }

    /**
     * Show the form for editing the specified meter.
     */
    public function edit(Meter $meter)
    {
        $consumers = Consumer::orderBy('name')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.meters.edit', compact('meter', 'consumers', 'subdivisions'));
    }

    /**
     * Update the specified meter.
     */
    public function update(Request $request, Meter $meter)
    {
        $oldValues = $meter->toArray();

        $validated = $request->validate([
            'meter_no' => 'required|string|unique:meters,meter_no,' . $meter->id,
            'meter_make' => 'nullable|string',
            'consumer_id' => 'nullable|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'installed_on' => 'nullable|date',
            'remarks' => 'nullable|string',
            'meter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('meter_image')) {
            // Delete old image if exists
            if ($meter->meter_image) {
                Storage::disk('public')->delete($meter->meter_image);
            }
            $imagePath = $request->file('meter_image')->store('meter_images', 'public');
            $validated['meter_image'] = $imagePath;
        }

        $meter->update($validated);

        self::logActivity('Meters', 'Updated', 'Meter', $meter->id, $oldValues, $validated);

        return redirect()->route('admin.meters.index')
            ->with('success', 'Meter updated successfully.');
    }

    /**
     * Remove the specified meter (soft delete).
     */
    public function destroy(Meter $meter)
    {
        // Check if meter has active bills
        if ($meter->bills()->count() > 0) {
            return redirect()->route('admin.meters.index')
                ->with('error', 'Cannot delete meter with existing billing records.');
        }

        $oldValues = $meter->toArray();
        $meter->delete(); // Soft delete

        self::logActivity('Meters', 'Deleted', 'Meter', $meter->id, $oldValues, null);

        return redirect()->route('admin.meters.index')
            ->with('success', 'Meter deleted successfully.');
    }

    /**
     * Update meter status.
     */
    public function updateStatus(Request $request, Meter $meter)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,faulty,disconnected',
        ]);

        $oldStatus = $meter->status;
        $meter->update(['status' => $validated['status']]);

        self::logActivity('Meters', 'Status Updated', 'Meter', $meter->id, 
            ['status' => $oldStatus], 
            ['status' => $validated['status']]
        );

        return redirect()->back()
            ->with('success', 'Meter status updated successfully.');
    }

    /**
     * Bulk import meters from Excel.
     */
    public function importForm()
    {
        return view('admin.meters.import');
    }

    /**
     * Export meters to Excel.
     */
    public function export(Request $request)
    {
        $query = Meter::with(['consumer', 'subdivision']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $meters = $query->get();

        // Here you would implement Excel export logic
        // For now, returning CSV format
        $filename = 'meters_export_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($meters) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Meter No', 'Make', 'Consumer', 'CNIC', 'Subdivision', 'Status', 'Installed On', 'Last Reading']);

            foreach ($meters as $meter) {
                fputcsv($file, [
                    $meter->meter_no,
                    $meter->meter_make,
                    $meter->consumer->name ?? 'N/A',
                    $meter->consumer->cnic ?? 'N/A',
                    $meter->subdivision->name ?? 'N/A',
                    $meter->status,
                    $meter->installed_on ? $meter->installed_on->format('Y-m-d') : 'N/A',
                    $meter->last_reading ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
