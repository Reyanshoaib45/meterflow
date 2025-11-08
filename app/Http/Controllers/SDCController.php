<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        // Show subdivisions that have SDC users assigned
        // Prefer subdivisions that have both SDC and LS, but show all with SDC
        $subdivisions = Subdivision::with(['company', 'lsUser'])
            ->whereHas('users', function($query) {
                $query->where('users.role', 'sdc');
            })
            ->withCount(['applications', 'meters'])
            ->orderByRaw('CASE WHEN ls_id IS NOT NULL THEN 0 ELSE 1 END') // Prioritize those with LS
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

        $loginValue = trim($credentials['username']);
        
        // Try to find user by username or email (case-insensitive)
        $user = User::where(function($query) use ($loginValue) {
                $query->whereRaw('LOWER(username) = ?', [strtolower($loginValue)])
                      ->orWhereRaw('LOWER(email) = ?', [strtolower($loginValue)]);
            })
            ->where('role', 'sdc')
            ->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'username' => 'No SDC user found with this username/email. Please check your credentials and try again.',
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

        $application = Application::with(['company', 'subdivision', 'meter', 'globalSummary'])
            ->where('assigned_sdc_id', $user->id)
            ->findOrFail($applicationId);

        // Calculate timer based on 72 hours from assignment
        // Get the latest history record when assigned_sdc_id was set
        $assignmentHistory = ApplicationHistory::where('application_id', $application->id)
            ->where('action_type', 'status_changed')
            ->where('remarks', 'like', '%assigned_sdc_id%')
            ->latest()
            ->first();
        
        $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $application->updated_at;
        $totalHoursAllowed = 72; // 72 hours total
        $hoursSinceAssignment = now()->diffInHours($assignmentTime);
        $hoursRemaining = max(0, $totalHoursAllowed - $hoursSinceAssignment);
        $canEdit = $hoursSinceAssignment < 24; // Can only edit within 24 hours
        
        // Get remaining time in seconds for JavaScript countdown
        $endTime = $assignmentTime->copy()->addHours($totalHoursAllowed);
        $remainingSeconds = max(0, $endTime->timestamp - now()->timestamp);

        // Get RO users assigned to this subdivision
        $roUsers = User::where('role', 'ro')
            ->orderBy('name')
            ->get();

        return view('sdc.edit-meter', compact('application', 'roUsers', 'assignmentTime', 'hoursRemaining', 'canEdit', 'remainingSeconds'));
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
            'assigned_ro_id' => 'nullable|exists:users,id',
            'noc_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            'demand_notice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // NOTE: Allow assigning any RO user (not limited to subdivision)
        if (!empty($validated['assigned_ro_id'])) {
            $roUser = User::where('id', $validated['assigned_ro_id'])
                ->where('role', 'ro')
                ->first();
            
            if (!$roUser) {
                return back()->withErrors([
                    'assigned_ro_id' => 'The selected RO user is invalid.',
                ])->withInput();
            }
        }

        // Find or create meter for this application
        // First check if meter exists for this application
        $meter = Meter::where('application_id', $application->id)->first();
        
        // If meter doesn't exist for this application, check if meter_no already exists
        if (!$meter) {
            $meter = Meter::where('meter_no', $application->meter_number)->first();
        }
        
        // If still no meter found, create new one
        if (!$meter) {
            $meter = new Meter();
            $meter->meter_no = $application->meter_number;
        }
        
        // Update meter details (only update meter_no if it's a new meter)
        if (!$meter->exists) {
            $meter->meter_no = $application->meter_number;
        }
        $meter->application_id = $application->id;
        $meter->subdivision_id = $application->subdivision_id;
        $meter->sim_number = $validated['sim_number'];
        $meter->seo_number = $validated['seo_number'];
        $meter->installed_on = $validated['installed_on'];
        $meter->status = 'active';
        $meter->save();

        // Update assigned RO if provided
        if (!empty($validated['assigned_ro_id'])) {
            $application->assigned_ro_id = $validated['assigned_ro_id'];
            $application->save();

            // Ensure the selected RO has access to this subdivision
            $roUser = User::find($validated['assigned_ro_id']);
            if ($roUser) {
                $roUser->subdivisions()->syncWithoutDetaching([$application->subdivision_id]);
            }
        }

        // Create history record
        $remarks = "Meter details updated by SDC: SIM Number: {$validated['sim_number']}, SEO Number: {$validated['seo_number']}, Installation Date: {$validated['installed_on']}";
        $sentToRO = false;
        if (!empty($validated['assigned_ro_id'])) {
            $roUser = User::find($validated['assigned_ro_id']);
            $remarks .= " | Assigned to RO: {$roUser->name}";
            $sentToRO = true;
        }
        
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'meter_details_updated',
            'remarks' => $remarks,
            'sent_to_ro' => $sentToRO,
            'seo_number' => $validated['seo_number'],
            'user_id' => $user->id,
        ]);

        // Handle file uploads
        $nocFilePath = null;
        $demandNoticeFilePath = null;
        
        if ($request->hasFile('noc_file')) {
            $nocFile = $request->file('noc_file');
            $nocFileName = 'noc_' . $application->id . '_' . time() . '.' . $nocFile->getClientOriginalExtension();
            $nocFilePath = $nocFile->storeAs('attachments/noc', $nocFileName, 'public');
        }
        
        if ($request->hasFile('demand_notice_file')) {
            $demandNoticeFile = $request->file('demand_notice_file');
            $demandNoticeFileName = 'demand_notice_' . $application->id . '_' . time() . '.' . $demandNoticeFile->getClientOriginalExtension();
            $demandNoticeFilePath = $demandNoticeFile->storeAs('attachments/demand_notice', $demandNoticeFileName, 'public');
        }

        // Save to GlobalSummary
        $globalSummary = GlobalSummary::firstOrNew(['application_id' => $application->id]);
        $globalSummary->application_no = $application->application_no;
        $globalSummary->customer_name = $application->customer_name;
        $globalSummary->meter_no = $application->meter_number;
        $globalSummary->sim_number = $validated['sim_number'];
        $globalSummary->customer_mobile_no = $application->phone;
        $globalSummary->date_on_draft_store = now();
        
        // Update file paths if new files were uploaded
        if ($nocFilePath) {
            // Delete old file if exists
            if ($globalSummary->noc_file && Storage::disk('public')->exists($globalSummary->noc_file)) {
                Storage::disk('public')->delete($globalSummary->noc_file);
            }
            $globalSummary->noc_file = $nocFilePath;
        }
        
        if ($demandNoticeFilePath) {
            // Delete old file if exists
            if ($globalSummary->demand_notice_file && Storage::disk('public')->exists($globalSummary->demand_notice_file)) {
                Storage::disk('public')->delete($globalSummary->demand_notice_file);
            }
            $globalSummary->demand_notice_file = $demandNoticeFilePath;
        }
        
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
            // sim_date column doesn't exist in global_summaries table
            // Storing sim_number instead
            $globalSummary->sim_number = $application->meter->sim_number;
        }

        return view('sdc.show-global-summary', compact('application', 'globalSummary'));
    }
}
