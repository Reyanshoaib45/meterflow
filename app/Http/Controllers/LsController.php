<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\GlobalSummary;
use App\Models\ExtraSummary;
use App\Models\Meter;
use App\Models\User;
use App\Traits\LogsActivity;

class LsController extends Controller
{
    use LogsActivity;

    /**
     * Show subdivision selection page for LS login.
     */
    public function selectSubdivision()
    {
        // Only show subdivisions that have an LS user assigned
        $subdivisions = Subdivision::with(['company', 'lsUser'])
            ->whereNotNull('ls_id')
            ->withCount(['applications', 'meters'])
            ->orderBy('name')
            ->get();
        
        return view('ls.select-subdivision', compact('subdivisions'));
    }

    /**
     * Show LS login page for specific subdivision.
     */
    public function showLogin($subdivisionId)
    {
        // Only allow login if subdivision has an LS user assigned
        $subdivision = Subdivision::with('company')
            ->whereNotNull('ls_id')
            ->findOrFail($subdivisionId);
        
        return view('ls.login', compact('subdivision'));
    }

    /**
     * Authenticate LS user.
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
            ->where('role', 'ls')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        // Check if user is assigned to this subdivision
        $subdivision = Subdivision::where('id', $credentials['subdivision_id'])
            ->where('ls_id', $user->id)
            ->first();

        if (!$subdivision) {
            return back()->withErrors([
                'username' => 'You are not authorized to access this subdivision.',
            ])->onlyInput('username');
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        // Store subdivision in session
        session(['current_subdivision_id' => $subdivision->id]);

        // Log activity
        self::logActivity('LS Login', 'Logged In', 'Subdivision', $subdivision->id, null, [
            'subdivision' => $subdivision->name,
        ]);

        return redirect()->route('ls.dashboard');
    }
    
    /**
     * Display the LS dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get subdivisions assigned to this LS user
        $subdivisions = Subdivision::where('ls_id', $user->id)
            ->with('company')
            ->get();
        
        // Get current subdivision from session or first subdivision
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        $currentSubdivision = Subdivision::find($currentSubdivisionId);
        
        // Statistics for current subdivision
        $stats = [
            'total_applications' => Application::where('subdivision_id', $currentSubdivisionId)->count(),
            'pending_applications' => Application::where('subdivision_id', $currentSubdivisionId)
                ->where('status', 'pending')
                ->count(),
            'approved_applications' => Application::where('subdivision_id', $currentSubdivisionId)
                ->where('status', 'approved')
                ->count(),
            'rejected_applications' => Application::where('subdivision_id', $currentSubdivisionId)
                ->where('status', 'rejected')
                ->count(),
            'total_meters' => Meter::where('subdivision_id', $currentSubdivisionId)->count(),
            'active_meters' => Meter::where('subdivision_id', $currentSubdivisionId)
                ->where('status', 'active')
                ->count(),
            'faulty_meters' => Meter::where('subdivision_id', $currentSubdivisionId)
                ->where('status', 'faulty')
                ->count(),
            'extra_summaries' => ExtraSummary::where('subdivision_id', $currentSubdivisionId)->count(),
        ];
        
        // Recent applications
        $recentApplications = Application::where('subdivision_id', $currentSubdivisionId)
            ->with(['company'])
            ->latest()
            ->take(10)
            ->get();
        
        // Application status chart data
        $statusChart = Application::where('subdivision_id', $currentSubdivisionId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Recent activity
        $recentActivity = ApplicationHistory::whereHas('application', function($q) use ($currentSubdivisionId) {
                $q->where('subdivision_id', $currentSubdivisionId);
            })
            ->with(['application', 'user'])
            ->latest()
            ->take(10)
            ->get();
            
        return view('ls.dashboard', compact(
            'subdivisions',
            'currentSubdivision',
            'stats',
            'recentApplications',
            'statusChart',
            'recentActivity'
        ));
    }
    
    /**
     * Display a listing of applications for a specific subdivision.
     */
    public function applications(Request $request, $subdivisionId)
    {
        $subdivision = Subdivision::findOrFail($subdivisionId);
        
        // Check if this subdivision belongs to the LS user
        if ($subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to subdivision');
        }
        
        // Update session
        session(['current_subdivision_id' => $subdivisionId]);
        
        $query = Application::where('subdivision_id', $subdivisionId)
            ->with(['company', 'meter']);
        
        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%");
            });
        }
        
        $applications = $query->latest()->paginate(15);
            
        return view('ls.applications', compact('subdivision', 'applications'));
    }
    
    /**
     * Show the form for editing an application status and fee.
     */
    public function editApplication($applicationId)
    {
        $application = Application::with(['subdivision', 'company'])->findOrFail($applicationId);
        
        // Check if this application belongs to a subdivision of the LS user
        if ($application->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to application');
        }
        
        return view('ls.edit-application', compact('application'));
    }
    
    /**
     * Update the application status and fee.
     */
    public function updateApplication(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if this application belongs to a subdivision of the LS user
        if ($application->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to application');
        }
        
        $oldStatus = $application->status;
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        $application->update([
            'status' => $validated['status'],
        ]);
        
        // Create application history
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'status_changed',
            'remarks' => $validated['remarks'] ?? "Status changed from {$oldStatus} to {$validated['status']}",
        ]);
        
        // Log activity
        self::logActivity('Applications', 'Status Updated', 'Application', $application->id, 
            ['status' => $oldStatus], 
            ['status' => $validated['status']]
        );
        
        return redirect()->route('ls.applications', $application->subdivision_id)
            ->with('success', 'Application status updated successfully.');
    }

    /**
     * Show application history.
     */
    public function applicationHistory($applicationId)
    {
        $application = Application::with(['subdivision', 'company'])->findOrFail($applicationId);
        
        // Check if this application belongs to a subdivision of the LS user
        if ($application->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to application');
        }
        
        $histories = ApplicationHistory::where('application_id', $applicationId)
            ->latest()
            ->get();
        
        return view('ls.application-history', compact('application', 'histories'));
    }
    
    /**
     * Show the form for creating a global summary.
     */
    public function createGlobalSummary($applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if this application belongs to a subdivision of the LS user
        if ($application->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to application');
        }
        
        return view('ls.create-global-summary', compact('application'));
    }
    
    /**
     * Store a newly created global summary.
     */
    public function storeGlobalSummary(Request $request, $applicationId)
    {
        $application = Application::findOrFail($applicationId);
        
        // Check if this application belongs to a subdivision of the LS user
        if ($application->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access to application');
        }
        
        $validated = $request->validate([
            'sim_date' => 'nullable|date',
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
        $validated['meter_no'] = $application->meter_no;
        
        GlobalSummary::create($validated);
        
        self::logActivity('Global Summary', 'Created', 'GlobalSummary', null, null, $validated);
        
        return redirect()->route('ls.applications', $application->subdivision_id)
            ->with('success', 'Global summary created successfully.');
    }

    /**
     * Display extra summaries.
     */
    public function extraSummaries()
    {
        $user = Auth::user();
        $subdivisions = Subdivision::where('ls_id', $user->id)->get();
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        
        $extraSummaries = ExtraSummary::where('subdivision_id', $currentSubdivisionId)
            ->with(['company', 'subdivision'])
            ->latest()
            ->paginate(15);
        
        return view('ls.extra-summaries', compact('extraSummaries', 'subdivisions', 'currentSubdivisionId'));
    }

    /**
     * Show form to create extra summary.
     */
    public function createExtraSummary()
    {
        $user = Auth::user();
        $subdivisions = Subdivision::where('ls_id', $user->id)->get();
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        $currentSubdivision = Subdivision::find($currentSubdivisionId);
        
        return view('ls.create-extra-summary', compact('currentSubdivision'));
    }

    /**
     * Store extra summary.
     */
    public function storeExtraSummary(Request $request)
    {
        $user = Auth::user();
        $currentSubdivisionId = session('current_subdivision_id');
        $subdivision = Subdivision::findOrFail($currentSubdivisionId);
        
        // Verify ownership
        if ($subdivision->ls_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }
        
        $validated = $request->validate([
            'total_applications' => 'required|integer|min:0',
            'approved_count' => 'required|integer|min:0',
            'rejected_count' => 'required|integer|min:0',
            'pending_count' => 'required|integer|min:0',
            'last_updated' => 'required|date',
        ]);
        
        $validated['company_id'] = $subdivision->company_id;
        $validated['subdivision_id'] = $subdivision->id;
        
        ExtraSummary::create($validated);
        
        self::logActivity('Extra Summary', 'Created', 'ExtraSummary', null, null, $validated);
        
        return redirect()->route('ls.extra-summaries')
            ->with('success', 'Extra summary created successfully.');
    }

    /**
     * Edit extra summary.
     */
    public function editExtraSummary($id)
    {
        $extraSummary = ExtraSummary::findOrFail($id);
        
        // Verify ownership
        if ($extraSummary->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access');
        }
        
        return view('ls.edit-extra-summary', compact('extraSummary'));
    }

    /**
     * Update extra summary.
     */
    public function updateExtraSummary(Request $request, $id)
    {
        $extraSummary = ExtraSummary::findOrFail($id);
        
        // Verify ownership
        if ($extraSummary->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access');
        }
        
        $oldValues = $extraSummary->toArray();
        
        $validated = $request->validate([
            'total_applications' => 'required|integer|min:0',
            'approved_count' => 'required|integer|min:0',
            'rejected_count' => 'required|integer|min:0',
            'pending_count' => 'required|integer|min:0',
            'last_updated' => 'required|date',
        ]);
        
        $extraSummary->update($validated);
        
        self::logActivity('Extra Summary', 'Updated', 'ExtraSummary', $id, $oldValues, $validated);
        
        return redirect()->route('ls.extra-summaries')
            ->with('success', 'Extra summary updated successfully.');
    }

    /**
     * Delete extra summary.
     */
    public function destroyExtraSummary($id)
    {
        $extraSummary = ExtraSummary::findOrFail($id);
        
        // Verify ownership
        if ($extraSummary->subdivision->ls_id !== Auth::user()->id) {
            abort(403, 'Unauthorized access');
        }
        
        $oldValues = $extraSummary->toArray();
        $extraSummary->delete();
        
        self::logActivity('Extra Summary', 'Deleted', 'ExtraSummary', $id, $oldValues, null);
        
        return redirect()->route('ls.extra-summaries')
            ->with('success', 'Extra summary deleted successfully.');
    }

    /**
     * View meter store.
     */
    public function meterStore()
    {
        $user = Auth::user();
        $subdivisions = Subdivision::where('ls_id', $user->id)->get();
        $currentSubdivisionId = session('current_subdivision_id', $subdivisions->first()->id ?? null);
        
        $meters = Meter::where('subdivision_id', $currentSubdivisionId)
            ->with(['consumer', 'application'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total' => Meter::where('subdivision_id', $currentSubdivisionId)->count(),
            'active' => Meter::where('subdivision_id', $currentSubdivisionId)->where('status', 'active')->count(),
            'faulty' => Meter::where('subdivision_id', $currentSubdivisionId)->where('status', 'faulty')->count(),
            'disconnected' => Meter::where('subdivision_id', $currentSubdivisionId)->where('status', 'disconnected')->count(),
        ];
        
        return view('ls.meter-store', compact('meters', 'stats', 'subdivisions', 'currentSubdivisionId'));
    }

    /**
     * Switch subdivision.
     */
    public function switchSubdivision(Request $request)
    {
        $subdivisionId = $request->input('subdivision_id');
        $subdivision = Subdivision::where('id', $subdivisionId)
            ->where('ls_id', Auth::user()->id)
            ->firstOrFail();
        
        session(['current_subdivision_id' => $subdivisionId]);
        
        return redirect()->back()->with('success', "Switched to {$subdivision->name}");
    }
}