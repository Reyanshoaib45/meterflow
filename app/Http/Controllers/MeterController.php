<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\Consumer;
use App\Models\Subdivision;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $meters = $query->latest()->paginate(20);
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
            'meter_make' => 'required|string',
            'consumer_id' => 'required|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'status' => 'required|in:active,faulty,disconnected',
            'installed_on' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $meter = Meter::create($validated);

        self::logActivity('Meters', 'Created', 'Meter', $meter->id, null, $validated);

        return redirect()->route('admin.meters.index')
            ->with('success', 'Meter created successfully.');
    }

    /**
     * Display the specified meter.
     */
    public function show(Meter $meter)
    {
        $meter->load(['consumer', 'subdivision', 'bills']);
        
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
            'meter_make' => 'required|string',
            'consumer_id' => 'required|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'status' => 'required|in:active,faulty,disconnected',
            'installed_on' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $meter->update($validated);

        self::logActivity('Meters', 'Updated', 'Meter', $meter->id, $oldValues, $validated);

        return redirect()->route('admin.meters.index')
            ->with('success', 'Meter updated successfully.');
    }

    /**
     * Remove the specified meter.
     */
    public function destroy(Meter $meter)
    {
        // Check if meter has active bills
        if ($meter->bills()->count() > 0) {
            return redirect()->route('admin.meters.index')
                ->with('error', 'Cannot delete meter with existing billing records.');
        }

        $oldValues = $meter->toArray();
        $meter->delete();

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
