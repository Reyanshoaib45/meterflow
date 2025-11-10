<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Subdivision;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    /**
     * Get admin dashboard statistics.
     */
    public function adminStats()
    {
        $stats = [
            'applications' => [
                'total' => Application::count(),
                'pending' => Application::where('status', 'pending')->count(),
                'approved' => Application::where('status', 'approved')->count(),
                'rejected' => Application::where('status', 'rejected')->count(),
            ],
            'consumers' => [
                'total' => Consumer::count(),
                'active' => Consumer::where('status', 'active')->count(),
                'disconnected' => Consumer::where('status', 'disconnected')->count(),
            ],
            'meters' => [
                'total' => Meter::count(),
                'active' => Meter::where('status', 'active')->count(),
                'faulty' => Meter::where('status', 'faulty')->count(),
                'in_store' => Meter::where('in_store', true)->count(),
            ],
            'bills' => [
                'total' => Bill::count(),
                'paid' => Bill::where('payment_status', 'paid')->count(),
                'unpaid' => Bill::where('payment_status', 'unpaid')->count(),
                'overdue' => Bill::where('payment_status', 'overdue')->count(),
                'total_revenue' => Bill::where('payment_status', 'paid')->sum('amount_paid'),
                'pending_amount' => Bill::whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
            ],
            'complaints' => [
                'total' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'in_progress' => Complaint::where('status', 'in_progress')->count(),
                'resolved' => Complaint::where('status', 'resolved')->count(),
            ],
            'subdivisions' => Subdivision::count(),
            'users' => [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'ls' => User::where('role', 'ls')->count(),
                'sdc' => User::where('role', 'sdc')->count(),
                'ro' => User::where('role', 'ro')->count(),
                'user' => User::where('role', 'user')->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get LS dashboard statistics.
     */
    public function lsStats(Request $request)
    {
        $user = Auth::user();
        $subdivisionId = $request->input('subdivision_id');

        if (!$subdivisionId) {
            $subdivision = Subdivision::where('ls_id', $user->id)->first();
            $subdivisionId = $subdivision ? $subdivision->id : null;
        }

        if (!$subdivisionId) {
            return response()->json([
                'success' => false,
                'message' => 'No subdivision assigned',
            ], 400);
        }

        $stats = [
            'applications' => [
                'total' => Application::where('subdivision_id', $subdivisionId)->count(),
                'pending' => Application::where('subdivision_id', $subdivisionId)->where('status', 'pending')->count(),
                'approved' => Application::where('subdivision_id', $subdivisionId)->where('status', 'approved')->count(),
            ],
            'meters' => [
                'total' => Meter::where('subdivision_id', $subdivisionId)->count(),
                'active' => Meter::where('subdivision_id', $subdivisionId)->where('status', 'active')->count(),
                'faulty' => Meter::where('subdivision_id', $subdivisionId)->where('status', 'faulty')->count(),
            ],
            'bills' => [
                'total' => Bill::where('subdivision_id', $subdivisionId)->count(),
                'pending' => Bill::where('subdivision_id', $subdivisionId)->where('payment_status', 'pending')->count(),
                'paid' => Bill::where('subdivision_id', $subdivisionId)->where('payment_status', 'paid')->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get recent activities.
     */
    public function recentActivities(Request $request)
    {
        $limit = $request->input('limit', 10);

        $recentApplications = Application::with(['company', 'subdivision'])
            ->latest()
            ->limit($limit)
            ->get();

        $recentBills = Bill::with(['consumer', 'meter'])
            ->latest()
            ->limit($limit)
            ->get();

        $recentComplaints = Complaint::with(['consumer', 'subdivision'])
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'applications' => $recentApplications,
                'bills' => $recentBills,
                'complaints' => $recentComplaints,
            ],
        ]);
    }

    /**
     * Get revenue trend.
     */
    public function revenueTrend(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];

        $data = Bill::select(
                'billing_month',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(amount_paid) as collected_revenue'),
                DB::raw('COUNT(*) as bill_count')
            )
            ->where('billing_year', $year)
            ->groupBy('billing_month')
            ->get()
            ->keyBy('billing_month');

        $trend = [];
        foreach ($months as $month) {
            $trend[] = [
                'month' => $month,
                'total_revenue' => $data->get($month)->total_revenue ?? 0,
                'collected_revenue' => $data->get($month)->collected_revenue ?? 0,
                'bill_count' => $data->get($month)->bill_count ?? 0,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $trend,
        ]);
    }

    /**
     * Get subdivision statistics.
     */
    public function subdivisionStats()
    {
        $subdivisions = Subdivision::with('company')
            ->withCount(['applications', 'meters'])
            ->get()
            ->map(function($subdivision) {
                return [
                    'id' => $subdivision->id,
                    'name' => $subdivision->name,
                    'code' => $subdivision->code,
                    'company' => $subdivision->company->name ?? 'N/A',
                    'applications_count' => $subdivision->applications_count,
                    'meters_count' => $subdivision->meters_count,
                    'bills_count' => Bill::where('subdivision_id', $subdivision->id)->count(),
                    'revenue' => Bill::where('subdivision_id', $subdivision->id)
                        ->where('payment_status', 'paid')
                        ->sum('amount_paid'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $subdivisions,
        ]);
    }
}
