<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Subdivision;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\GlobalSummary;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin panel dashboard.
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Enhanced statistics
        $stats = [
            'subdivisions' => Subdivision::count(),
            'consumers' => Consumer::count(),
            'meters' => [
                'total' => Meter::count(),
                'active' => Meter::where('status', 'active')->count(),
                'faulty' => Meter::where('status', 'faulty')->count(),
                'disconnected' => Meter::where('status', 'disconnected')->count(),
            ],
            'revenue' => [
                'this_month' => Bill::where('billing_month', date('F'))
                    ->where('billing_year', date('Y'))
                    ->sum('amount_paid'),
                'total' => Bill::where('payment_status', 'paid')->sum('amount_paid'),
                'pending' => Bill::whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
            ],
            'power_loss' => [
                'total_supplied' => Bill::sum('units_consumed'),
                'total_billed' => Bill::sum('units_consumed'),
                'loss_percentage' => 0, // Calculate based on your logic
            ],
            'complaints' => [
                'total' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'in_progress' => Complaint::where('status', 'in_progress')->count(),
                'resolved' => Complaint::where('status', 'resolved')->count(),
            ],
            'users' => User::count(),
            'sdo_users' => User::where('role', 'ls')->count(),
        ];
        
        // Revenue trend (last 6 months)
        $revenueTrend = Bill::select(
                DB::raw('DATE_FORMAT(issue_date, "%Y-%m") as month'),
                DB::raw('SUM(amount_paid) as revenue')
            )
            ->where('issue_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Complaint trend (last 30 days)
        $complaintTrend = Complaint::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Area-wise comparison
        $subdivisionStats = Subdivision::withCount(['consumers', 'meters', 'bills', 'complaints'])
            ->with(['bills' => function($q) {
                $q->select('subdivision_id', DB::raw('SUM(amount_paid) as total_revenue'))
                  ->groupBy('subdivision_id');
            }])
            ->limit(10)
            ->get();
        
        $recentApplications = Application::with(['company', 'subdivision'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentComplaints = Complaint::with(['consumer', 'subdivision'])
            ->latest()
            ->take(5)
            ->get();
        
        // Applications by status
        $applicationStats = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
            
        // Bills summary
        $billStats = [
            'total_bills' => Bill::count(),
            'paid' => Bill::where('payment_status', 'paid')->count(),
            'unpaid' => Bill::where('payment_status', 'unpaid')->count(),
            'overdue' => Bill::where('payment_status', 'overdue')->count(),
        ];
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentApplications', 
            'recentComplaints', 
            'revenueTrend',
            'complaintTrend',
            'subdivisionStats',
            'applicationStats',
            'billStats'
        ));
    }
    
    /**
     * Display the companies management page.
     */
    public function companies()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $companies = Company::latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }
    
    /**
     * Show the form for creating a new company.
     */
    public function createCompany()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        return view('admin.companies.create');
    }
    
    /**
     * Store a newly created company.
     */
    public function storeCompany(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies',
            'code' => 'required|string|max:50|unique:companies',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);
        
        Company::create($validated);
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company created successfully.');
    }
    
    /**
     * Show the form for editing a company.
     */
    public function editCompany(Company $company)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        return view('admin.companies.edit', compact('company'));
    }
    
    /**
     * Update the specified company.
     */
    public function updateCompany(Request $request, Company $company)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'code' => 'required|string|max:50|unique:companies,code,' . $company->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $company->update($validated);
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company updated successfully.');
    }
    
    /**
     * Remove the specified company.
     */
    public function destroyCompany(Company $company)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        // Check if company has subdivisions or applications
        if ($company->subdivisions()->count() > 0) {
            return redirect()->route('admin.companies')
                ->with('error', 'Cannot delete company with existing subdivisions.');
        }
        
        $company->delete();
        
        return redirect()->route('admin.companies')
            ->with('success', 'Company deleted successfully.');
    }
    
    /**
     * Display the subdivisions management page.
     */
    public function subdivisions()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $subdivisions = Subdivision::with(['company', 'lsUser'])
            ->latest()
            ->paginate(15);
            
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.index', compact('subdivisions', 'companies', 'lsUsers'));
    }
    
    /**
     * Show the form for creating a new subdivision.
     */
    public function createSubdivision()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.create', compact('companies', 'lsUsers'));
    }
    
    /**
     * Store a newly created subdivision.
     */
    public function storeSubdivision(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subdivisions',
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        Subdivision::create($validated);
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision created successfully.');
    }
    
    /**
     * Show the form for editing a subdivision.
     */
    public function editSubdivision(Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $companies = Company::all();
        $lsUsers = User::where('role', 'ls')->get();
        
        return view('admin.subdivisions.edit', compact('subdivision', 'companies', 'lsUsers'));
    }
    
    /**
     * Update the specified subdivision.
     */
    public function updateSubdivision(Request $request, Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subdivisions,code,' . $subdivision->id,
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        $subdivision->update($validated);
        
        return redirect()->route('admin.subdivisions')
            ->with('success', 'Subdivision updated successfully.');
    }
    
    /**
     * Remove the specified subdivision.
     */
    public function destroySubdivision(Subdivision $subdivision)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
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
     * Display the users management page.
     */
    public function users()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $users = User::latest()->paginate(15);
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
        
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.users.create', compact('subdivisions'));
    }
    
    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,ls',
            'subdivision_id' => 'required_if:role,ls|nullable|exists:subdivisions,id',
        ]);
        
        $validated['password'] = bcrypt($validated['password']);
        
        $user = User::create($validated);
        
        // Update subdivision ls_id if LS user is created
        if ($request->role === 'ls' && $request->subdivision_id) {
            Subdivision::where('id', $request->subdivision_id)->update(['ls_id' => $user->id]);
        }
        
        return redirect()->route('admin.users')
            ->with('success', 'User created successfully.');
    }
    
    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user,ls',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
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
        
        $applications = Application::with(['company', 'subdivision'])
            ->latest()
            ->paginate(15);
            
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
            'remarks' => 'nullable|string',
        ]);
        
        $oldStatus = $application->status;
        $application->update([
            'status' => $validated['status'],
            'fee_amount' => $validated['fee_amount'],
            'meter_number' => $validated['meter_number'],
        ]);
        
        // Create history record
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'status_changed',
            'remarks' => $validated['remarks'] ?? "Status changed from {$oldStatus} to {$validated['status']} by admin",
            'user_id' => Auth::id(),
        ]);
        
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
}