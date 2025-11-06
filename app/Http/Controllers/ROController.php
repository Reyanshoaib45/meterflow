<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subdivision;
use App\Models\User;
use App\Models\GlobalSummary;
use App\Models\Bill;
use App\Models\ApplicationHistory;
use App\Traits\LogsActivity;

class ROController extends Controller
{
    use LogsActivity;

        /**
     * Show subdivision selection page for RO login.
     */
    public function selectSubdivision()
    {
        // Show all subdivisions that have RO users assigned via pivot table    
        $subdivisions = Subdivision::with(['company'])
            ->whereHas('users', function($query) {
                $query->where('users.role', 'ro');
            })
            ->withCount(['applications', 'meters'])
            ->orderBy('name')
            ->get();
        
        return view('Ls.select-subdivision', compact('subdivisions'));
    }

    /**
     * Show RO login page for specific subdivision.
     */
    public function showLogin($subdivisionId)
    {
        // Check if subdivision has RO users assigned
        $subdivision = Subdivision::with('company')
            ->whereHas('users', function($query) {
                $query->where('users.role', 'ro');
            })
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

        $loginValue = trim($credentials['username']);
        
        // Try to find user by username or email (case-insensitive)
        $user = User::where(function($query) use ($loginValue) {
                $query->whereRaw('LOWER(username) = ?', [strtolower($loginValue)])
                      ->orWhereRaw('LOWER(email) = ?', [strtolower($loginValue)]);
            })
            ->where('role', 'ro')
            ->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'username' => 'No RO user found with this username/email. Please check your credentials and try again.',
            ])->onlyInput('username');
        }

        // Check password
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password you entered is incorrect. Please try again.',
            ])->onlyInput('username');
        }

        // Check if user is assigned to this subdivision via pivot table
        $subdivision = Subdivision::where('id', $credentials['subdivision_id'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$subdivision) {
            return back()->withErrors([
                'username' => 'You are not authorized to access this subdivision. Please contact admin to assign you to this subdivision.',
            ])->onlyInput('username');
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        // Store subdivision in session
        session(['current_subdivision_id' => $subdivision->id]);

        // Log activity
        self::logActivity('RO Login', 'Logged In', 'Subdivision', $subdivision->id, null, [
            'subdivision' => $subdivision->name,
        ]);

        return redirect()->route('ro.dashboard');
    }

    /**
     * Display RO dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        // Get subdivisions assigned to this RO user
        $subdivisions = $user->subdivisions()->with('company')->get();
        
        // Get current subdivision from session or first subdivision
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        $currentSubdivision = $currentSubdivisionId ? Subdivision::find($currentSubdivisionId) : null;

        // If current subdivision has no assigned summaries, try to choose one that has
        if (!$currentSubdivision && $subdivisions->isNotEmpty()) {
            $currentSubdivision = $subdivisions->first();
            $currentSubdivisionId = $currentSubdivision->id;
        }

        if ($currentSubdivision) {
            $hasAssigned = GlobalSummary::whereHas('application', function($query) use ($currentSubdivisionId, $user) {
                    $query->where('subdivision_id', $currentSubdivisionId)
                          ->where('assigned_ro_id', $user->id);
                })->exists();

            if (!$hasAssigned) {
                // Find a subdivision among RO's list that has assigned summaries
                $candidate = Subdivision::whereIn('id', $subdivisions->pluck('id'))
                    ->whereHas('applications', function($q) use ($user) {
                        $q->where('assigned_ro_id', $user->id);
                    })
                    ->first();
                if ($candidate) {
                    $currentSubdivision = $candidate;
                    $currentSubdivisionId = $candidate->id;
                    session(['current_subdivision_id' => $currentSubdivisionId]);
                }
            }
        }

        // Get summaries assigned to this RO user for current subdivision
        $summariesForRO = collect();
        if ($currentSubdivision) {
            // Get global summaries where application is assigned to this RO user
            // Show if assigned_ro_id matches, regardless of sent_to_ro flag
            $summariesForRO = GlobalSummary::whereHas('application', function($query) use ($currentSubdivisionId, $user) {
                    $query->where('subdivision_id', $currentSubdivisionId)
                          ->where('assigned_ro_id', $user->id);
                })
                ->with(['application.histories', 'application.assignedRO', 'application.meter'])
                ->latest()
                ->paginate(15);
        }

        // Statistics
        $stats = [
            'total_summaries' => 0,
            'pending_billing' => 0,
            'total_bills' => 0,
        ];

        if ($currentSubdivision) {
            $stats['total_summaries'] = GlobalSummary::whereHas('application', function($query) use ($currentSubdivisionId, $user) {
                    $query->where('subdivision_id', $currentSubdivisionId)
                          ->where('assigned_ro_id', $user->id);
                })
                ->count();

            // Count summaries without bills (check if application has meter with bills)
            $summariesWithBills = GlobalSummary::whereHas('application', function($query) use ($currentSubdivisionId, $user) {
                    $query->where('subdivision_id', $currentSubdivisionId)
                          ->where('assigned_ro_id', $user->id);
                })
                ->whereHas('application.meter.bills')
                ->count();
            
            $stats['pending_billing'] = max(0, $stats['total_summaries'] - $summariesWithBills);

            $stats['total_bills'] = Bill::where('subdivision_id', $currentSubdivisionId)->count();
        }

        return view('ro.dashboard', compact(
            'subdivisions',
            'currentSubdivision',
            'summariesForRO',
            'stats'
        ));
    }

    /**
     * Switch subdivision.
     */
    public function switchSubdivision(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        $subdivisionId = $request->input('subdivision_id');
        $subdivision = $user->subdivisions()->findOrFail($subdivisionId);
        
        session(['current_subdivision_id' => $subdivisionId]);
        
        return redirect()->back()->with('success', "Switched to {$subdivision->name}");
    }

    /**
     * Manage billing for a summary assigned to the current RO.
     */
    public function manageBilling($summaryId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        $summary = GlobalSummary::with(['application.subdivision', 'application.meter', 'application.assignedRO'])
            ->findOrFail($summaryId);
        
        // Ensure this summary's application is assigned to current RO
        if (!$summary->application || $summary->application->assigned_ro_id !== $user->id) {
            abort(403, 'You are not assigned to this application.');
        }

        // Ensure current subdivision matches (if set)
        $currentSubdivisionId = session('current_subdivision_id');
        if ($currentSubdivisionId && $summary->application->subdivision_id != $currentSubdivisionId) {
            // Auto-switch to the correct subdivision for convenience
            session(['current_subdivision_id' => $summary->application->subdivision_id]);
        }

        $currentSubdivision = $summary->application ? $summary->application->subdivision : null;

        return view('ro.manage-billing', [
            'summary' => $summary,
            'currentSubdivision' => $currentSubdivision,
        ]);
    }

    /**
     * Create a bill for the given summary (assigned to current RO), then redirect to invoice.
     */
    public function createBill(Request $request, $summaryId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        $summary = GlobalSummary::with(['application.meter.consumer', 'application.subdivision'])
            ->findOrFail($summaryId);
        
        if (!$summary->application || $summary->application->assigned_ro_id !== $user->id) {
            abort(403, 'You are not assigned to this application.');
        }

        // Validate minimal fields for now (can be expanded later)
        $validated = $request->validate([
            'billing_month' => 'nullable|integer|min:1|max:12',
            'billing_year' => 'nullable|integer|min:2000|max:2100',
        ]);

        $application = $summary->application;
        $meter = $application->meter;

        // Generate a simple bill number
        $billNo = 'BILL-' . now()->format('Ymd-His') . '-' . $application->id;

        $bill = new Bill();
        $bill->bill_no = $billNo;
        $bill->consumer_id = $meter ? $meter->consumer_id : null;
        $bill->meter_id = $meter ? $meter->id : null;
        $bill->subdivision_id = $application->subdivision_id;
        $bill->billing_month = $validated['billing_month'] ?? (int) now()->format('n');
        $bill->billing_year = $validated['billing_year'] ?? (int) now()->format('Y');
        $bill->previous_reading = $meter ? ($meter->last_reading ?? 0) : 0;
        $bill->current_reading = $meter ? ($meter->reading ?? 0) : 0;
        $bill->units_consumed = max(0, ($bill->current_reading - $bill->previous_reading));
        $bill->rate_per_unit = 0; // can be configured later
        $bill->energy_charges = 0;
        $bill->fixed_charges = 0;
        $bill->gst_amount = 0;
        $bill->tv_fee = 0;
        $bill->meter_rent = 0;
        $bill->late_payment_surcharge = 0;
        $bill->total_amount = 0; // placeholder until tariff logic added
        $bill->amount_paid = 0;
        $bill->payment_status = 'unpaid';
        $bill->issue_date = now();
        $bill->due_date = now()->addDays(15);
        $bill->is_verified = false;
        $bill->verified_by = null;
        $bill->remarks = 'Generated by RO';
        $bill->save();

        // Mark summary as completed by setting return to billing date
        $summary->date_return_sdc_billing = now();
        $summary->save();

        // History record
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'bill_created',
            'remarks' => "Bill created by RO {$user->name} | Bill No: {$bill->bill_no}",
            'user_id' => $user->id,
        ]);

        return redirect()->route('ro.invoice', $bill->id)
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Show simple invoice for the created bill.
     */
    public function invoice($billId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        $bill = Bill::with(['meter.application.subdivision', 'consumer', 'subdivision'])
            ->findOrFail($billId);

        // Ensure RO has access to the subdivision
        $subdivisionId = $bill->subdivision_id;
        if (!$user->subdivisions()->where('subdivision_id', $subdivisionId)->exists()) {
            abort(403, 'You do not have access to this subdivision.');
        }

        return view('ro.invoice', compact('bill'));
    }

    /**
     * Show full summary details to the assigned RO.
     */
    public function viewSummary($summaryId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ro') {
            abort(403, 'Unauthorized access. RO role required.');
        }

        $summary = GlobalSummary::with([
                'application.subdivision',
                'application.assignedRO',
                'application.meter',
            ])->findOrFail($summaryId);
        
        if (!$summary->application || $summary->application->assigned_ro_id !== $user->id) {
            abort(403, 'You are not assigned to this application.');
        }

        // Fetch histories (prefer those with SEO number shown in the view table)
        $histories = ApplicationHistory::where('application_id', $summary->application->id)
            ->orderByDesc('created_at')
            ->get();

        return view('ro.summary-details', compact('summary', 'histories'));
    }
}
