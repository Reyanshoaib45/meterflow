<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subdivision;
use App\Models\GlobalSummary;
use App\Models\ApplicationHistory;
use App\Models\Bill;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Tariff;
use App\Models\User;
use App\Traits\LogsActivity;

class ROController extends Controller
{
    use LogsActivity;

    /**
     * Show subdivision selection page for RO login.
     */
    public function selectSubdivision()
    {
        // Show all subdivisions for RO access
        $subdivisions = Subdivision::with(['company'])
            ->withCount(['applications'])
            ->orderBy('name')
            ->get();
        
        return view('Ls.select-subdivision', compact('subdivisions'));
    }

    /**
     * Show RO login page for specific subdivision.
     */
    public function showLogin($subdivisionId)
    {
        $subdivision = Subdivision::with('company')
            ->findOrFail($subdivisionId);
        
        return view('Ls.login', compact('subdivision'));
    }

    /**
     * Authenticate RO user.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'subdivision_id' => 'required|exists:subdivisions,id',
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])
            ->where('role', 'ro')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        // Store subdivision in session
        session(['current_subdivision_id' => $credentials['subdivision_id']]);

        // Log activity
        self::logActivity('RO Login', 'Logged In', 'Subdivision', $credentials['subdivision_id'], null, [
            'subdivision' => Subdivision::find($credentials['subdivision_id'])->name,
        ]);

        return redirect()->route('ro.dashboard');
    }
    
    /**
     * Display the RO dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $subdivisions = Subdivision::orderBy('name')->get();
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        $currentSubdivision = $currentSubdivisionId ? Subdivision::with('company')->find($currentSubdivisionId) : null;

        // Get summaries sent to RO (with seo_number and sent_to_ro = true)
        $summariesForRO = GlobalSummary::whereHas('application', function($q) use ($currentSubdivisionId) {
            if ($currentSubdivisionId) {
                $q->where('subdivision_id', $currentSubdivisionId);
            }
        })
        ->whereHas('application.histories', function($q) {
            $q->where('sent_to_ro', true);
        })
        ->with(['application.histories' => function($q) {
            $q->where('sent_to_ro', true)->latest();
        }])
        ->latest()
        ->paginate(15);

        // Get statistics
        $stats = [
            'total_summaries' => $summariesForRO->total(),
            'pending_billing' => Bill::whereHas('consumer.application', function($q) use ($currentSubdivisionId) {
                if ($currentSubdivisionId) {
                    $q->where('subdivision_id', $currentSubdivisionId);
                }
            })->where('status', 'pending')->count(),
            'total_bills' => Bill::whereHas('consumer.application', function($q) use ($currentSubdivisionId) {
                if ($currentSubdivisionId) {
                    $q->where('subdivision_id', $currentSubdivisionId);
                }
            })->count(),
        ];

        return view('ro.dashboard', compact('subdivisions', 'currentSubdivision', 'stats', 'summariesForRO'));
    }

    /**
     * Switch subdivision for RO user.
     */
    public function switchSubdivision(Request $request)
    {
        $validated = $request->validate([
            'subdivision_id' => 'required|exists:subdivisions,id',
        ]);

        session(['current_subdivision_id' => $validated['subdivision_id']]);

        return redirect()->route('ro.dashboard')
            ->with('success', 'Subdivision switched successfully.');
    }

    /**
     * View summary details.
     */
    public function viewSummary($id)
    {
        $summary = GlobalSummary::with(['application.subdivision', 'application.company'])
            ->findOrFail($id);

        $histories = ApplicationHistory::where('application_id', $summary->application_id)
            ->where('sent_to_ro', true)
            ->latest()
            ->get();

        return view('ro.summary-details', compact('summary', 'histories'));
    }

    /**
     * Manage billing for a summary.
     */
    public function manageBilling($summaryId)
    {
        $summary = GlobalSummary::with(['application.consumer', 'application.meter'])->findOrFail($summaryId);
        
        // Get consumer from application
        $application = $summary->application;
        $consumer = Consumer::where('cnic', $application->cnic)->first();
        $meter = $application->meter;
        
        // Get existing bills for this consumer/meter
        $bills = collect();
        if ($consumer) {
            $bills = Bill::where('consumer_id', $consumer->id)
                ->with(['consumer', 'meter'])
                ->latest()
                ->get();
        } elseif ($meter) {
            $bills = Bill::where('meter_id', $meter->id)
                ->with(['consumer', 'meter'])
                ->latest()
                ->get();
        }
        
        return view('ro.manage-billing', compact('summary', 'consumer', 'meter', 'bills'));
    }

    /**
     * Create bill for summary.
     */
    public function createBill(Request $request, $summaryId)
    {
        $summary = GlobalSummary::with('application')->findOrFail($summaryId);
        $application = $summary->application;
        
        $consumer = Consumer::where('cnic', $application->cnic)->first();
        $meter = $application->meter;
        
        if (!$consumer || !$meter) {
            return back()->with('error', 'Consumer or meter not found for this application.');
        }
        
        $validated = $request->validate([
            'billing_month' => 'required|string',
            'billing_year' => 'required|integer|min:2020|max:2100',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|gte:previous_reading',
            'due_date' => 'required|date',
            'issue_date' => 'required|date',
        ]);
        
        // Calculate units
        $unitsConsumed = $validated['current_reading'] - $validated['previous_reading'];
        
        // Get tariff
        $tariff = Tariff::where('is_active', true)
            ->where(function($q) use ($unitsConsumed) {
                $q->where('from_units', '<=', $unitsConsumed)
                  ->where(function($query) use ($unitsConsumed) {
                      $query->whereNull('to_units')
                            ->orWhere('to_units', '>=', $unitsConsumed);
                  });
            })
            ->first();
        
        if (!$tariff) {
            return back()->with('error', 'No active tariff found for this consumption level.');
        }
        
        // Calculate charges
        $energyCharges = $unitsConsumed * $tariff->rate_per_unit;
        $gstAmount = ($energyCharges * ($tariff->gst_percentage ?? 0)) / 100;
        $totalAmount = $energyCharges + ($tariff->fixed_charges ?? 0) + $gstAmount + ($tariff->tv_fee ?? 0) + ($tariff->meter_rent ?? 0);
        
        // Generate bill number
        $billNumber = 'BILL-' . date('Ym') . '-' . str_pad(Bill::count() + 1, 6, '0', STR_PAD_LEFT);
        
        $billData = array_merge($validated, [
            'bill_number' => $billNumber,
            'consumer_id' => $consumer->id,
            'meter_id' => $meter->id,
            'subdivision_id' => $application->subdivision_id,
            'units_consumed' => $unitsConsumed,
            'rate_per_unit' => $tariff->rate_per_unit,
            'energy_charges' => $energyCharges,
            'fixed_charges' => $tariff->fixed_charges ?? 0,
            'gst_amount' => $gstAmount,
            'tv_fee' => $tariff->tv_fee ?? 0,
            'meter_rent' => $tariff->meter_rent ?? 0,
            'total_amount' => $totalAmount,
            'payment_status' => 'unpaid',
        ]);
        
        $bill = Bill::create($billData);
        
        // Update meter reading
        $meter->update([
            'last_reading' => $validated['current_reading'],
            'last_reading_date' => now(),
        ]);
        
        self::logActivity('RO Billing', 'Created', 'Bill', $bill->id, null, $billData);
        
        return redirect()->route('ro.manage-billing', $summaryId)
            ->with('success', 'Bill created successfully.');
    }
}
