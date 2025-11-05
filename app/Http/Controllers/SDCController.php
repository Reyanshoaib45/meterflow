<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subdivision;
use App\Models\User;
use App\Models\Application;
use App\Models\Meter;
use App\Models\ApplicationHistory;
use App\Models\GlobalSummary;
use App\Traits\LogsActivity;

class SDCController extends Controller
{
    use LogsActivity;

        /**
     * Show subdivision selection page for SDC login.
     */
    public function selectSubdivision()
    {
        // Show all subdivisions that have SDC users assigned via pivot table   
        $subdivisions = Subdivision::with(['company'])
            ->whereHas('users', function($query) {
                $query->where('users.role', 'sdc');
            })
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
        // Check if subdivision has SDC users assigned
        $subdivision = Subdivision::with('company')
            ->whereHas('users', function($query) {
                $query->where('users.role', 'sdc');
            })
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

        // Check if user is assigned to this subdivision via pivot table
        $subdivision = Subdivision::where('id', $credentials['subdivision_id'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
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
        self::logActivity('SDC Login', 'Logged In', 'Subdivision', $subdivision->id, null, [
            'subdivision' => $subdivision->name,
        ]);

        return redirect()->route('sdc.dashboard');
    }

    /**
     * Display SDC dashboard with assigned applications.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'sdc') {
            abort(403, 'Unauthorized access. SDC role required.');
        }

        // Get applications assigned to this SDC user
        $applications = Application::where('assigned_sdc_id', $user->id)
            ->with(['company', 'subdivision', 'meter'])
            ->latest()
            ->paginate(15);

        // Get current subdivision from session
        $currentSubdivisionId = session('current_subdivision_id');
        $currentSubdivision = $currentSubdivisionId ? Subdivision::find($currentSubdivisionId) : null;

        return view('sdc.dashboard', compact('applications', 'currentSubdivision'));
    }

    /**
     * Show form to update meter details for an application.
     */
    public function editMeter($applicationId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'sdc') {
            abort(403, 'Unauthorized access. SDC role required.');
        }

        $application = Application::with(['company', 'subdivision', 'meter'])
            ->where('assigned_sdc_id', $user->id)
            ->findOrFail($applicationId);

        // Check if 24 hours have passed since assignment
        // Get the latest history record when assigned_sdc_id was set
        $assignmentHistory = ApplicationHistory::where('application_id', $application->id)
            ->where('action_type', 'status_changed')
            ->where('remarks', 'like', '%assigned_sdc_id%')
            ->latest()
            ->first();
        
        $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $application->updated_at;
        $hoursSinceAssignment = now()->diffInHours($assignmentTime);
        
        if ($hoursSinceAssignment > 24) {
            return redirect()->route('sdc.dashboard')
                ->with('error', 'You can only edit meter details within 24 hours of assignment. Please contact admin for changes.');
        }

        return view('sdc.edit-meter', compact('application'));
    }

    /**
     * Update meter details for an application.
     */
    public function updateMeter(Request $request, $applicationId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'sdc') {
            abort(403, 'Unauthorized access. SDC role required.');
        }

        $application = Application::where('assigned_sdc_id', $user->id)
            ->findOrFail($applicationId);

        // Check if 24 hours have passed since assignment
        // Get the latest history record when assigned_sdc_id was set
        $assignmentHistory = ApplicationHistory::where('application_id', $application->id)
            ->where('action_type', 'status_changed')
            ->where('remarks', 'like', '%assigned_sdc_id%')
            ->latest()
            ->first();
        
        $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $application->updated_at;
        $hoursSinceAssignment = now()->diffInHours($assignmentTime);
        
        if ($hoursSinceAssignment > 24) {
            return back()->with('error', 'You can only edit meter details within 24 hours of assignment. Please contact admin for changes.');
        }

        $validated = $request->validate([
            'sim_number' => 'required|string|max:50',
            'seo_number' => 'required|string|max:50',
            'installed_on' => 'required|date',
        ]);

        // Find or create meter for this application
        $meter = Meter::firstOrNew(['application_id' => $application->id]);
        
        // Update meter details
        $meter->meter_no = $application->meter_number;
        $meter->subdivision_id = $application->subdivision_id;
        $meter->sim_number = $validated['sim_number'];
        $meter->seo_number = $validated['seo_number'];
        $meter->installed_on = $validated['installed_on'];
        $meter->status = 'active';
        $meter->save();

        // Create history record
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'meter_details_updated',
            'remarks' => "Meter details updated by SDC: SIM Number: {$validated['sim_number']}, SEO Number: {$validated['seo_number']}, Installation Date: {$validated['installed_on']}",
            'user_id' => $user->id,
        ]);

        // Save to GlobalSummary
        $globalSummary = GlobalSummary::firstOrNew(['application_id' => $application->id]);
        $globalSummary->application_no = $application->application_no;
        $globalSummary->customer_name = $application->customer_name;
        $globalSummary->meter_no = $application->meter_number;
        $globalSummary->sim_date = $validated['installed_on'];
        $globalSummary->customer_mobile_no = $application->phone;
        $globalSummary->date_on_draft_store = now();
        $globalSummary->save();

        return redirect()->route('sdc.dashboard')
            ->with('success', 'Meter details updated successfully.');
    }

    /**
     * Request application deletion from admin.
     */
    public function requestDeletion($applicationId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'sdc') {
            abort(403, 'Unauthorized access. SDC role required.');
        }

        $application = Application::where('assigned_sdc_id', $user->id)
            ->findOrFail($applicationId);

        // Create history record for deletion request
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'deletion_requested',
            'remarks' => "SDC user {$user->name} has requested deletion of this application. Please contact admin.",
            'user_id' => $user->id,
        ]);

        return redirect()->route('sdc.dashboard')
            ->with('success', 'Deletion request has been sent to admin. Please contact admin for further action.');
    }

    /**
     * Show global summary for an assigned application.
     */
    public function showGlobalSummary($applicationId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'sdc') {
            abort(403, 'Unauthorized access. SDC role required.');
        }

        $application = Application::with(['company', 'subdivision', 'meter'])
            ->where('assigned_sdc_id', $user->id)
            ->findOrFail($applicationId);

        // Get or create global summary for this application
        $globalSummary = GlobalSummary::firstOrNew(['application_id' => $application->id]);
        
        // If global summary doesn't exist, populate it with application data
        if (!$globalSummary->exists) {
            $globalSummary->application_no = $application->application_no;
            $globalSummary->customer_name = $application->customer_name;
            $globalSummary->meter_no = $application->meter_number;
            $globalSummary->customer_mobile_no = $application->phone;
        }
        
        // Load meter details if available
        if ($application->meter) {
            $globalSummary->sim_date = $application->meter->installed_on;
        }

        return view('sdc.show-global-summary', compact('application', 'globalSummary'));
    }
}
