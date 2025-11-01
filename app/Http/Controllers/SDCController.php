<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\GlobalSummary;
use App\Models\User;
use App\Traits\LogsActivity;

class SDCController extends Controller
{
    use LogsActivity;

    /**
     * Show subdivision selection page for SDC login.
     */
    public function selectSubdivision()
    {
        // Show all subdivisions for SDC access
        $subdivisions = Subdivision::with(['company'])
            ->withCount(['applications', 'meters'])
            ->orderBy('name')
            ->get();
        
        return view('Ls.select-subdivision', compact('subdivisions'));
    }

    /**
     * Show SDC login page for specific subdivision.
     */
    public function showLogin($subdivisionId)
    {
        $subdivision = Subdivision::with('company')
            ->findOrFail($subdivisionId);
        
        return view('Ls.login', compact('subdivision'));
    }

    /**
     * Authenticate SDC user.
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
            ->where('role', 'sdc')
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
        self::logActivity('SDC Login', 'Logged In', 'Subdivision', $credentials['subdivision_id'], null, [
            'subdivision' => Subdivision::find($credentials['subdivision_id'])->name,
        ]);

        return redirect()->route('sdc.dashboard');
    }
    
    /**
     * Display the SDC dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $subdivisions = Subdivision::orderBy('name')->get();
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        $currentSubdivision = $currentSubdivisionId ? Subdivision::with('company')->find($currentSubdivisionId) : null;

        // Get statistics for current subdivision
        $stats = [
            'total_applications' => Application::where('subdivision_id', $currentSubdivisionId)->count(),
            'pending_applications' => Application::where('subdivision_id', $currentSubdivisionId)->where('status', 'pending')->count(),
            'approved_applications' => Application::where('subdivision_id', $currentSubdivisionId)->where('status', 'approved')->count(),
            'total_summaries' => GlobalSummary::whereHas('application', function($q) use ($currentSubdivisionId) {
                $q->where('subdivision_id', $currentSubdivisionId);
            })->count(),
        ];

        // Get global summaries for current subdivision
        $globalSummaries = GlobalSummary::whereHas('application', function($q) use ($currentSubdivisionId) {
            $q->where('subdivision_id', $currentSubdivisionId);
        })
        ->with('application')
        ->latest()
        ->paginate(15);

        return view('sdc.dashboard', compact('subdivisions', 'currentSubdivision', 'stats', 'globalSummaries'));
    }

    /**
     * Switch subdivision for SDC user.
     */
    public function switchSubdivision(Request $request)
    {
        $validated = $request->validate([
            'subdivision_id' => 'required|exists:subdivisions,id',
        ]);

        session(['current_subdivision_id' => $validated['subdivision_id']]);

        return redirect()->route('sdc.dashboard')
            ->with('success', 'Subdivision switched successfully.');
    }

    /**
     * Display global summaries list.
     */
    public function globalSummaries()
    {
        $currentSubdivisionId = session('current_subdivision_id');
        $subdivisions = Subdivision::orderBy('name')->get();

        $globalSummaries = GlobalSummary::whereHas('application', function($q) use ($currentSubdivisionId) {
            if ($currentSubdivisionId) {
                $q->where('subdivision_id', $currentSubdivisionId);
            }
        })
        ->with('application')
        ->latest()
        ->paginate(15);

        return view('sdc.global-summaries.index', compact('globalSummaries', 'subdivisions', 'currentSubdivisionId'));
    }

    /**
     * Show the form for creating a global summary.
     */
    public function createGlobalSummary($applicationId)
    {
        $application = Application::with(['subdivision', 'company'])->findOrFail($applicationId);
        return view('sdc.global-summaries.create', compact('application'));
    }
    
    /**
     * Store a newly created global summary.
     */
    public function storeGlobalSummary(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        $validated = $request->validate([
            'sim_number' => 'nullable|string|max:50',
            'consumer_address' => 'nullable|string',
            'date_on_draft_store' => 'nullable|date',
            'date_received_lm_consumer' => 'nullable|date',
            'customer_mobile_no' => 'nullable|string|max:20',
            'customer_sc_no' => 'nullable|string|max:50',
            'date_return_sdc_billing' => 'nullable|date',
        ]);
        
        // Add application data to validated data
        $validated['application_id'] = $application->id;
        $validated['application_no'] = $application->application_no;
        $validated['customer_name'] = $application->customer_name;
        $validated['consumer_address'] = $application->address ?? $validated['consumer_address'] ?? null;
        $validated['meter_no'] = $application->meter_number;
        
        GlobalSummary::create($validated);
        
        self::logActivity('Global Summary', 'Created', 'GlobalSummary', null, null, $validated);
        
        return redirect()->route('sdc.global-summaries')
            ->with('success', 'Global summary created successfully.');
    }

    /**
     * Show the form for editing a global summary.
     */
    public function editGlobalSummary($id)
    {
        $globalSummary = GlobalSummary::with('application')->findOrFail($id);
        return view('sdc.global-summaries.edit', compact('globalSummary'));
    }

    /**
     * Update the specified global summary.
     */
    public function updateGlobalSummary(Request $request, $id)
    {
        $globalSummary = GlobalSummary::findOrFail($id);
        
        $validated = $request->validate([
            'sim_number' => 'nullable|string|max:50',
            'consumer_address' => 'nullable|string',
            'date_on_draft_store' => 'nullable|date',
            'date_received_lm_consumer' => 'nullable|date',
            'customer_mobile_no' => 'nullable|string|max:20',
            'customer_sc_no' => 'nullable|string|max:50',
            'date_return_sdc_billing' => 'nullable|date',
        ]);
        
        $globalSummary->update($validated);
        
        self::logActivity('Global Summary', 'Updated', 'GlobalSummary', $id, null, $validated);
        
        return redirect()->route('sdc.global-summaries')
            ->with('success', 'Global summary updated successfully.');
    }
}
