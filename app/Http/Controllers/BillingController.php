<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Subdivision;
use App\Models\Tariff;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of bills.
     */
    public function index(Request $request)
    {
        $query = Bill::with(['consumer', 'meter', 'subdivision']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_no', 'like', "%{$search}%")
                  ->orWhereHas('consumer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->filled('month')) {
            $query->where('billing_month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        // Use 27 records for initial load, 15 for subsequent pages
        $perPage = $request->get('page', 1) == 1 ? 27 : 15;
        $bills = $query->latest()->paginate($perPage);
        $subdivisions = Subdivision::orderBy('name')->get();

        // Statistics
        $stats = [
            'total_bills' => Bill::count(),
            'paid_bills' => Bill::where('payment_status', 'paid')->count(),
            'unpaid_bills' => Bill::where('payment_status', 'unpaid')->count(),
            'overdue_bills' => Bill::where('payment_status', 'overdue')->count(),
            'total_revenue' => Bill::where('payment_status', 'paid')->sum('amount_paid'),
            'pending_amount' => Bill::whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
        ];

        return view('admin.billing.index', compact('bills', 'subdivisions', 'stats'));
    }

    /**
     * Show the form for creating a new bill.
     */
    public function create()
    {
        $consumers = Consumer::where('status', 'active')->orderBy('name')->get();
        $meters = Meter::where('status', 'active')->with('consumer')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.billing.create', compact('consumers', 'meters', 'subdivisions'));
    }

    /**
     * Store a newly created bill.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'meter_id' => 'required|exists:meters,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'billing_month' => 'required|string',
            'billing_year' => 'required|integer',
            'previous_reading' => 'required|numeric',
            'current_reading' => 'required|numeric|gte:previous_reading',
            'due_date' => 'required|date',
            'issue_date' => 'required|date',
        ]);

        // Calculate units and charges
        $unitsConsumed = $validated['current_reading'] - $validated['previous_reading'];
        
        // Get consumer and tariff
        $consumer = Consumer::find($validated['consumer_id']);
        $tariff = Tariff::active()
            ->forConnectionType($consumer->connection_type)
            ->where('from_units', '<=', $unitsConsumed)
            ->where(function($q) use ($unitsConsumed) {
                $q->whereNull('to_units')
                  ->orWhere('to_units', '>=', $unitsConsumed);
            })
            ->first();

        if (!$tariff) {
            return back()->with('error', 'No active tariff found for this consumption level.');
        }

        $energyCharges = $unitsConsumed * $tariff->rate_per_unit;
        $gstAmount = ($energyCharges * $tariff->gst_percentage) / 100;
        $totalAmount = $energyCharges + $tariff->fixed_charges + $gstAmount + $tariff->tv_fee + $tariff->meter_rent;

        // Generate bill number
        $billNumber = 'BILL-' . date('Ym') . '-' . str_pad(Bill::count() + 1, 6, '0', STR_PAD_LEFT);

        $billData = array_merge($validated, [
            'bill_no' => $billNumber,
            'units_consumed' => $unitsConsumed,
            'rate_per_unit' => $tariff->rate_per_unit,
            'energy_charges' => $energyCharges,
            'fixed_charges' => $tariff->fixed_charges,
            'gst_amount' => $gstAmount,
            'tv_fee' => $tariff->tv_fee,
            'meter_rent' => $tariff->meter_rent,
            'total_amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ]);

        $bill = Bill::create($billData);

        // Update meter last reading
        $meter = Meter::find($validated['meter_id']);
        $meter->update([
            'last_reading' => $validated['current_reading'],
            'last_reading_date' => now(),
        ]);

        self::logActivity('Billing', 'Created', 'Bill', $bill->id, null, $billData);

        return redirect()->route('admin.billing.index')
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified bill.
     */
    public function show(Bill $bill)
    {
        $bill->load(['consumer', 'meter', 'subdivision', 'verifier']);
        
        return view('admin.billing.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified bill.
     */
    public function edit(Bill $bill)
    {
        if ($bill->is_verified) {
            return redirect()->route('admin.billing.index')
                ->with('error', 'Cannot edit verified bills.');
        }

        $consumers = Consumer::orderBy('name')->get();
        $meters = Meter::with('consumer')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.billing.edit', compact('bill', 'consumers', 'meters', 'subdivisions'));
    }

    /**
     * Update the specified bill.
     */
    public function update(Request $request, Bill $bill)
    {
        if ($bill->is_verified) {
            return redirect()->route('admin.billing.index')
                ->with('error', 'Cannot edit verified bills.');
        }

        $oldValues = $bill->toArray();

        $validated = $request->validate([
            'current_reading' => 'required|numeric|gte:' . $bill->previous_reading,
            'due_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        // Recalculate charges
        $unitsConsumed = $validated['current_reading'] - $bill->previous_reading;
        $consumer = $bill->consumer;
        
        $tariff = Tariff::active()
            ->forConnectionType($consumer->connection_type)
            ->where('from_units', '<=', $unitsConsumed)
            ->where(function($q) use ($unitsConsumed) {
                $q->whereNull('to_units')
                  ->orWhere('to_units', '>=', $unitsConsumed);
            })
            ->first();

        if (!$tariff) {
            return back()->with('error', 'No active tariff found for this consumption level.');
        }

        $energyCharges = $unitsConsumed * $tariff->rate_per_unit;
        $gstAmount = ($energyCharges * $tariff->gst_percentage) / 100;
        $totalAmount = $energyCharges + $tariff->fixed_charges + $gstAmount + $tariff->tv_fee + $tariff->meter_rent;

        $bill->update([
            'current_reading' => $validated['current_reading'],
            'units_consumed' => $unitsConsumed,
            'rate_per_unit' => $tariff->rate_per_unit,
            'energy_charges' => $energyCharges,
            'fixed_charges' => $tariff->fixed_charges,
            'gst_amount' => $gstAmount,
            'tv_fee' => $tariff->tv_fee,
            'meter_rent' => $tariff->meter_rent,
            'total_amount' => $totalAmount,
            'due_date' => $validated['due_date'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        self::logActivity('Billing', 'Updated', 'Bill', $bill->id, $oldValues, $bill->toArray());

        return redirect()->route('admin.billing.index')
            ->with('success', 'Bill updated successfully.');
    }

    /**
     * Verify a bill.
     */
    public function verify(Bill $bill)
    {
        $bill->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
        ]);

        self::logActivity('Billing', 'Verified', 'Bill', $bill->id, 
            ['is_verified' => false], 
            ['is_verified' => true, 'verified_by' => Auth::id()]
        );

        return redirect()->back()
            ->with('success', 'Bill verified successfully.');
    }

    /**
     * Generate bills for a month.
     */
    public function generateForm()
    {
        $subdivisions = Subdivision::orderBy('name')->get();
        return view('admin.billing.generate', compact('subdivisions'));
    }

    /**
     * Process bulk bill generation.
     */
    public function generateBills(Request $request)
    {
        $validated = $request->validate([
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'billing_month' => 'required|string',
            'billing_year' => 'required|integer',
            'due_date' => 'required|date',
        ]);

        $query = Meter::where('status', 'active')->with('consumer');

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $meters = $query->get();
        $generatedCount = 0;
        $errors = [];

        foreach ($meters as $meter) {
            try {
                // Check if bill already exists
                $existingBill = Bill::where('meter_id', $meter->id)
                    ->where('billing_month', $validated['billing_month'])
                    ->where('billing_year', $validated['billing_year'])
                    ->first();

                if ($existingBill) {
                    continue;
                }

                if (!$meter->consumer) {
                    $errors[] = "Meter {$meter->meter_no} has no consumer assigned.";
                    continue;
                }

                $previousReading = $meter->last_reading ?? 0;
                $currentReading = $previousReading; // In real scenario, this would come from meter reading

                $unitsConsumed = $currentReading - $previousReading;
                
                $tariff = Tariff::active()
                    ->forConnectionType($meter->consumer->connection_type)
                    ->where('from_units', '<=', $unitsConsumed)
                    ->where(function($q) use ($unitsConsumed) {
                        $q->whereNull('to_units')
                          ->orWhere('to_units', '>=', $unitsConsumed);
                    })
                    ->first();

                if (!$tariff) {
                    $errors[] = "No tariff found for meter {$meter->meter_no}";
                    continue;
                }

                $energyCharges = $unitsConsumed * $tariff->rate_per_unit;
                $gstAmount = ($energyCharges * $tariff->gst_percentage) / 100;
                $totalAmount = $energyCharges + $tariff->fixed_charges + $gstAmount + $tariff->tv_fee + $tariff->meter_rent;

                $billNumber = 'BILL-' . $validated['billing_year'] . $validated['billing_month'] . '-' . str_pad(Bill::count() + 1, 6, '0', STR_PAD_LEFT);

                Bill::create([
                    'bill_no' => $billNumber,
                    'consumer_id' => $meter->consumer_id,
                    'meter_id' => $meter->id,
                    'subdivision_id' => $meter->subdivision_id,
                    'billing_month' => $validated['billing_month'],
                    'billing_year' => $validated['billing_year'],
                    'previous_reading' => $previousReading,
                    'current_reading' => $currentReading,
                    'units_consumed' => $unitsConsumed,
                    'rate_per_unit' => $tariff->rate_per_unit,
                    'energy_charges' => $energyCharges,
                    'fixed_charges' => $tariff->fixed_charges,
                    'gst_amount' => $gstAmount,
                    'tv_fee' => $tariff->tv_fee,
                    'meter_rent' => $tariff->meter_rent,
                    'total_amount' => $totalAmount,
                    'payment_status' => 'unpaid',
                    'due_date' => $validated['due_date'],
                    'issue_date' => now(),
                ]);

                $generatedCount++;
            } catch (\Exception $e) {
                $errors[] = "Error generating bill for meter {$meter->meter_no}: " . $e->getMessage();
            }
        }

        self::logActivity('Billing', 'Bulk Generated', null, null, null, [
            'count' => $generatedCount,
            'month' => $validated['billing_month'],
            'year' => $validated['billing_year'],
        ]);

        $message = "Generated {$generatedCount} bills successfully.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " errors occurred.";
        }

        return redirect()->route('admin.billing.index')
            ->with('success', $message)
            ->with('errors', $errors);
    }

    /**
     * Download bill PDF.
     */
    public function downloadPdf(Bill $bill)
    {
        $bill->load(['consumer', 'meter', 'subdivision']);
        
        // Here you would implement PDF generation logic
        // For now, returning a view
        return view('admin.billing.pdf', compact('bill'));
    }

    /**
     * Export bills to Excel.
     */
    public function export(Request $request)
    {
        $query = Bill::with(['consumer', 'meter', 'subdivision']);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->filled('month')) {
            $query->where('billing_month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        $bills = $query->get();

        $filename = 'bills_export_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($bills) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Bill Number', 'Consumer', 'CNIC', 'Month', 'Year', 'Units', 'Amount', 'Status', 'Due Date']);

            foreach ($bills as $bill) {
                fputcsv($file, [
                    $bill->bill_no,
                    $bill->consumer->name ?? 'N/A',
                    $bill->consumer->cnic ?? 'N/A',
                    $bill->billing_month,
                    $bill->billing_year,
                    $bill->units_consumed,
                    $bill->total_amount,
                    $bill->payment_status,
                    $bill->due_date->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
