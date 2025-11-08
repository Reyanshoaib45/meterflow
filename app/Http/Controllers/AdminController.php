<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\AuditLog;
use App\Services\AdminDashboardService;
use App\Services\CompanyService;
use App\Services\SubdivisionService;
use App\Services\UserManagementService;

class AdminController extends Controller
{
    protected $dashboardService;
    protected $companyService;
    protected $subdivisionService;
    protected $userService;

    public function __construct(
        AdminDashboardService $dashboardService,
        CompanyService $companyService,
        SubdivisionService $subdivisionService,
        UserManagementService $userService
    ) {
        $this->dashboardService = $dashboardService;
        $this->companyService = $companyService;
        $this->subdivisionService = $subdivisionService;
        $this->userService = $userService;
    }

    /**
     * Display the admin panel dashboard.
     */
    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        $revenueTrend = $this->dashboardService->getRevenueTrend();
        $complaintTrend = $this->dashboardService->getComplaintTrend();
        $subdivisionStats = $this->dashboardService->getSubdivisionStats();
        $recentApplications = $this->dashboardService->getRecentApplications();
        $recentComplaints = $this->dashboardService->getRecentComplaints();
        $recentUsers = $this->dashboardService->getRecentUsers();
        $applicationStats = $this->dashboardService->getApplicationStats();
        $billStats = $this->dashboardService->getBillStats();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentApplications', 
            'recentComplaints', 
            'revenueTrend',
            'complaintTrend',
            'subdivisionStats',
            'applicationStats',
            'billStats',
            'recentUsers'
        ));
    }
    
    /**
     * Display the companies management page.
     */
    public function companies()
    {
        $companies = $this->companyService->getPaginatedCompanies();
        return view('admin.companies.index', compact('companies'));
    }
    
    /**
     * Show the form for creating a new company.
     */
    public function createCompany()
    {
        return view('admin.companies.create');
    }
    
    /**
     * Store a newly created company.
     */
    public function storeCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies',
            'code' => 'required|string|max:50|unique:companies',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $this->companyService->create($validated);
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company created successfully.');
    }
    
    /**
     * Show the form for editing a company.
     */
    public function editCompany(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }
    
    /**
     * Update the specified company.
     */
    public function updateCompany(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'code' => 'required|string|max:50|unique:companies,code,' . $company->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $this->companyService->update($company, $validated);
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company updated successfully.');
    }
    
    /**
     * Remove the specified company.
     */
    public function destroyCompany(Company $company)
    {
        // Check if company has subdivisions
        if ($company->subdivisions()->count() > 0) {
            return redirect()->route('admin.companies')
                ->with('error', 'Cannot delete company with existing subdivisions.');
        }
        
        $this->companyService->delete($company);
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company deleted successfully.');
    }
    
    /**
     * Display the subdivisions management page.
     */
    public function subdivisions()
    {
        $subdivisions = $this->subdivisionService->getPaginatedSubdivisions();
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.index', compact('subdivisions', 'companies', 'lsUsers'));
    }
    
    /**
     * Show the form for creating a new subdivision.
     */
    public function createSubdivision()
    {
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.create', compact('companies', 'lsUsers'));
    }
    
    /**
     * Store a newly created subdivision.
     */
    public function storeSubdivision(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subdivisions',
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        $this->subdivisionService->create($validated);
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision created successfully.');
    }
    
    /**
     * Show the form for editing a subdivision.
     */
    public function editSubdivision(Subdivision $subdivision)
    {
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.edit', compact('subdivision', 'companies', 'lsUsers'));
    }
    
    /**
     * Update the specified subdivision.
     */
    public function updateSubdivision(Request $request, Subdivision $subdivision)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subdivisions,code,' . $subdivision->id,
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        $this->subdivisionService->update($subdivision, $validated);
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision updated successfully.');
    }
    
    /**
     * Remove the specified subdivision.
     */
    public function destroySubdivision(Subdivision $subdivision)
    {
        
        // Check if subdivision has applications
        if ($subdivision->applications()->count() > 0) {
            return redirect()->route('admin.subdivisions')
                ->with('error', 'Cannot delete subdivision with existing applications.');
        }
        
        $subdivision->delete();
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision deleted successfully.');
    }

    /**
     * Show form to edit subdivision message.
     */
    public function editSubdivisionMessage(Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        return view('admin.subdivisions.message', compact('subdivision'));
    }

    /**
     * Update subdivision message.
     */
    public function updateSubdivisionMessage(Request $request, Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'subdivision_message' => 'nullable|string|max:500',
        ]);
        
        $subdivision->update($validated);
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision message updated successfully.');
    }
    
    /**
     * Display the users management page.
     */
    public function users()
    {
        // Use UserManagementService for better organization
        $users = $this->userService->getPaginatedUsers();
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $subdivisions = Subdivision::with('company')->orderBy('name')->get();
        
        return response()->view('admin.users.create', compact('subdivisions'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Email validation - optional for SDC and RO roles, but ALWAYS unique when provided
        if (in_array($request->role, ['sdc', 'ro'])) {
            // For SDC/RO, email can be optional but if provided, must be valid email format and unique
            if (!empty($request->email)) {
                // Email provided: validate format and uniqueness
                $emailRules = 'nullable|string|email:rfc,dns|max:255|unique:users,email';
            } else {
                // Email empty: allow NULL
                $emailRules = 'nullable|string|max:255';
            }
        } else {
            // For LS and other roles, email is required and must be unique
            $emailRules = 'required|string|email:rfc,dns|max:255|unique:users,email';   
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRules,
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,ls,sdc,ro',
            'subdivision_ids' => 'required_if:role,ls|required_if:role,sdc|required_if:role,ro|array',
            'subdivision_ids.*' => 'exists:subdivisions,id',
        ], [
            'email.email' => 'Please enter a valid email address (e.g., user@example.com).',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered. Please use a different email address.',
        ]);

        // If email is empty for SDC/RO, set it to NULL
        if (in_array($request->role, ['sdc', 'ro']) && empty($validated['email'])) {
            $validated['email'] = null;
        }
        
        $validated['password'] = bcrypt($validated['password']);
        
        // Remove subdivision_ids from user data (not a column in users table)
        $subdivisionIds = $validated['subdivision_ids'] ?? [];
        unset($validated['subdivision_ids']);
        
        // Set the first subdivision as primary subdivision_id for all roles that have subdivisions
        if (!empty($subdivisionIds) && in_array($request->role, ['ls', 'sdc', 'ro'])) {
            $validated['subdivision_id'] = $subdivisionIds[0];
        }
        
        $user = User::create($validated);
        
        // Handle subdivision assignments based on role
        if (!empty($subdivisionIds)) {
            if ($request->role === 'ls') {
                // For LS: Update subdivisions with ls_id
                Subdivision::whereIn('id', $subdivisionIds)->update(['ls_id' => $user->id]);
            } elseif (in_array($request->role, ['sdc', 'ro'])) {
                // For SDC/RO: Use many-to-many pivot table
                $user->subdivisions()->attach($subdivisionIds);
            }
        }
        
        // Redirect to create page with success message
        return redirect()->route('admin.users.create')
            ->with('success', 'User created successfully' . (!empty($subdivisionIds) ? ' and assigned to ' . count($subdivisionIds) . ' subdivision(s).' : '.'));
    }
    
    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $subdivisions = Subdivision::with('company')->orderBy('name')->get();
        
        return view('admin.users.edit', compact('user', 'subdivisions'));
    }
    
    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Email validation - optional for SDC and RO roles, but ALWAYS unique when provided
        if (in_array($request->role, ['sdc', 'ro'])) {
            // For SDC/RO, email can be optional but if provided, must be valid email format and unique
            if (!empty($request->email)) {
                // Email provided: validate format and uniqueness (ignore current user)
                $emailRules = 'nullable|string|email:rfc,dns|max:255|unique:users,email,' . $user->id;
            } else {
                // Email empty: allow NULL
                $emailRules = 'nullable|string|max:255';
            }
        } else {
            // For LS and other roles, email is required and must be unique (ignore current user)
            $emailRules = 'required|string|email:rfc,dns|max:255|unique:users,email,' . $user->id;   
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRules,
            'role' => 'required|in:admin,user,ls,sdc,ro',
            'password' => 'nullable|string|min:8|confirmed',
            'subdivision_ids' => 'required_if:role,ls|required_if:role,sdc|required_if:role,ro|array',
            'subdivision_ids.*' => 'exists:subdivisions,id',
        ], [
            'email.email' => 'Please enter a valid email address (e.g., user@example.com).',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already registered. Please use a different email address.',
        ]);
        
        // If email is empty for SDC/RO, set it to NULL
        if (in_array($request->role, ['sdc', 'ro']) && empty($validated['email'])) {
            $validated['email'] = null;
        }
        
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle subdivision assignments
        $subdivisionIds = $validated['subdivision_ids'] ?? [];
        unset($validated['subdivision_ids']);
        
        // Set the first subdivision as primary subdivision_id
        if ($request->role === 'ls' && !empty($subdivisionIds)) {
            $validated['subdivision_id'] = $subdivisionIds[0];
        } else {
            $validated['subdivision_id'] = null;
        }
        
        $user->update($validated);
        
        // Update subdivision assignments for LS users
        if ($request->role === 'ls' && !empty($subdivisionIds)) {
            // Remove this user from previously assigned subdivisions
            Subdivision::where('ls_id', $user->id)->update(['ls_id' => null]);
            
            // Assign to new subdivisions
            Subdivision::whereIn('id', $subdivisionIds)->update(['ls_id' => $user->id]);
        } else {
            // If no longer LS user, remove from all subdivisions
            Subdivision::where('ls_id', $user->id)->update(['ls_id' => null]);
        }
        
        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroyUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
    
    /**
     * Display the applications management page.
     */
    public function applications()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Use 27 records for initial load, 15 for subsequent pages
        $perPage = request()->get('page', 1) == 1 ? 27 : 15;
        $applications = Application::with(['company', 'subdivision'])
            ->latest()
            ->paginate($perPage);
            
        return view('admin.applications.index', compact('applications'));
    }
    
    /**
     * Display the application logs/activity logs page.
     */
    public function activityLogs()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $logs = ApplicationHistory::with(['application', 'user'])
            ->latest()
            ->paginate(20);
            
        return view('admin.activity-logs.index', compact('logs'));
    }
    
    /**
     * Show the form for editing an application.
     */
    public function editApplication(Application $application)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        return view('admin.applications.edit', compact('application'));
    }
    
    /**
     * Update the specified application.
     */
    public function updateApplication(Request $request, Application $application)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,closed',
            'fee_amount' => 'nullable|numeric|min:0',
            'meter_number' => 'nullable|string|max:255',
            'assigned_sdc_id' => 'nullable|exists:users,id',
            'remarks' => 'nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $application->update([
            'status' => $validated['status'],
            'fee_amount' => $validated['fee_amount'],
            'meter_number' => $validated['meter_number'],
            'assigned_sdc_id' => $validated['assigned_sdc_id'] ?? null,
        ]);
        
        // Create history record
        $remarks = $validated['remarks'] ?? "Status changed from {$oldStatus} to {$validated['status']} by admin";
        if (!empty($validated['assigned_sdc_id'])) {
            $sdcUser = \App\Models\User::find($validated['assigned_sdc_id']);
            $remarks .= " | Assigned to SDC: {$sdcUser->name} (assigned_sdc_id: {$validated['assigned_sdc_id']})";
        }
        
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'status_changed',
            'remarks' => $remarks,
            'user_id' => Auth::id(),
        ]);
        
        // Save to GlobalSummary (create or update)
        $globalSummary = \App\Models\GlobalSummary::firstOrNew(['application_id' => $application->id]);
        $globalSummary->application_no = $application->application_no;
        $globalSummary->customer_name = $application->customer_name;
        $globalSummary->meter_no = $application->meter_number;
        $globalSummary->customer_mobile_no = $application->phone;
        $globalSummary->date_on_draft_store = now();
        $globalSummary->save();
        
        return redirect()->route('admin.applications')
            ->with('success', 'Application updated successfully.');
    }
    
    /**
     * Show application history.
     */
    public function applicationHistory(Application $application)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $history = ApplicationHistory::where('application_id', $application->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.applications.history', compact('application', 'history'));
    }

    /**
     * Delete the specified application.
     */
    public function destroyApplication(Application $application)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        // Save to GlobalSummary before deletion
        $globalSummary = \App\Models\GlobalSummary::firstOrNew(['application_id' => $application->id]);
        $globalSummary->application_no = $application->application_no;
        $globalSummary->customer_name = $application->customer_name;
        $globalSummary->meter_no = $application->meter_number;
        $globalSummary->customer_mobile_no = $application->phone;
        $globalSummary->date_on_draft_store = now();
        $globalSummary->save();

        // Create history record
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'deleted',
            'remarks' => "Application deleted by admin: {$application->application_no}. Deletion saved to GlobalSummary.",
            'user_id' => Auth::id(),
        ]);

        // Delete the application
        $applicationNo = $application->application_no;
        $application->delete();

        return redirect()->route('admin.applications')
            ->with('success', "Application {$applicationNo} has been deleted successfully. Deletion record saved to GlobalSummary.");
    }
    
    /**
     * Display LS Management page.
     */
    public function lsManagement()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $lsUsers = User::where('role', 'ls')
            ->with('assignedSubdivision')
            ->paginate(15);
            
        $subdivisions = Subdivision::with(['company', 'lsUser'])
            ->paginate(15);
        
        return view('admin.ls-management.index', compact('lsUsers', 'subdivisions'));
    }
    
    /**
     * Show LS permissions page.
     */
    public function lsPermissions(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        if ($user->role !== 'ls') {
            abort(404, 'User is not an LS user.');
        }
        
        $permissions = json_decode($user->permissions ?? '[]', true);
        
        return view('admin.ls-management.permissions', compact('user', 'permissions'));
    }
    
    /**
     * Update LS permissions.
     */
    public function updateLsPermissions(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $permissions = $request->input('permissions', []);
        
        $user->update([
            'permissions' => json_encode($permissions)
        ]);
        
        return redirect()->route('admin.ls-management')
            ->with('success', 'LS permissions updated successfully.');
    }
    
    /**
     * Suspend LS user.
     */
    public function suspendLsUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $user->update(['is_active' => false]);
        
        return redirect()->route('admin.ls-management')
            ->with('success', 'LS user suspended successfully.');
    }
    
    /**
     * Activate LS user.
     */
    public function activateLsUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $user->update(['is_active' => true]);
        
        return redirect()->route('admin.ls-management')
            ->with('success', 'LS user activated successfully.');
    }
    
    /**
     * Temporarily close subdivision.
     */
    public function closeSubdivision(Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $subdivision->update(['is_active' => false]);
        
        return redirect()->route('admin.ls-management')
            ->with('success', 'Subdivision temporarily closed.');
    }
    
    /**
     * Reopen subdivision.
     */
    public function openSubdivision(Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $subdivision->update(['is_active' => true]);
        
        return redirect()->route('admin.ls-management')
            ->with('success', 'Subdivision reopened successfully.');
    }
    
    /**
     * Export all system data.
     */
    public function exportData(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $type = $request->get('type', 'consumers');
        
        switch ($type) {
            case 'consumers':
                return $this->exportConsumers();
            case 'applications':
                return $this->exportApplications();
            case 'bills':
                return $this->exportBills();
            case 'meters':
                return $this->exportMeters();
            case 'complaints':
                return $this->exportComplaints();
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }
    
    private function exportConsumers()
    {
        $consumers = Consumer::with('subdivision')->get();
        $filename = 'consumers_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($consumers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'CNIC', 'Phone', 'Address', 'Subdivision', 'Connection Type', 'Status', 'Created At']);
            
            foreach ($consumers as $consumer) {
                fputcsv($file, [
                    $consumer->id,
                    $consumer->name,
                    $consumer->cnic,
                    $consumer->phone,
                    $consumer->address,
                    $consumer->subdivision->name ?? 'N/A',
                    $consumer->connection_type,
                    $consumer->status,
                    $consumer->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportApplications()
    {
        $applications = Application::with(['subdivision', 'company'])->get();
        $filename = 'applications_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Application No', 'Customer Name', 'CNIC', 'Phone', 'Subdivision', 'Status', 'Fee Amount', 'Created At']);
            
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->application_no,
                    $app->customer_name,
                    $app->cnic,
                    $app->phone,
                    $app->subdivision->name ?? 'N/A',
                    $app->status,
                    $app->fee_amount,
                    $app->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportBills()
    {
        $bills = Bill::with(['consumer', 'subdivision'])->get();
        $filename = 'bills_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($bills) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Bill No', 'Consumer', 'Month', 'Year', 'Units', 'Amount', 'Paid', 'Status', 'Due Date']);
            
            foreach ($bills as $bill) {
                fputcsv($file, [
                    $bill->bill_no,
                    $bill->consumer->name ?? 'N/A',
                    $bill->billing_month,
                    $bill->billing_year,
                    $bill->units_consumed,
                    $bill->total_amount,
                    $bill->amount_paid,
                    $bill->payment_status,
                    $bill->due_date,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportMeters()
    {
        $meters = Meter::with(['consumer', 'subdivision'])->get();
        $filename = 'meters_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($meters) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Meter No', 'Consumer', 'Make', 'Reading', 'Status', 'Subdivision', 'Installed On']);
            
            foreach ($meters as $meter) {
                fputcsv($file, [
                    $meter->meter_no,
                    $meter->consumer->name ?? 'N/A',
                    $meter->meter_make,
                    $meter->reading,
                    $meter->status,
                    $meter->subdivision->name ?? 'N/A',
                    $meter->installed_on,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function exportComplaints()
    {
        $complaints = Complaint::with(['consumer', 'subdivision'])->get();
        $filename = 'complaints_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($complaints) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Complaint ID', 'Consumer', 'Subject', 'Type', 'Status', 'Priority', 'Created At']);
            
            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->complaint_id,
                    $complaint->consumer->name ?? 'N/A',
                    $complaint->subject,
                    $complaint->complaint_type ?? 'General',
                    $complaint->status,
                    $complaint->priority ?? 'normal',
                    $complaint->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}