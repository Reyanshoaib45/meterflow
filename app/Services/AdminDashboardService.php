<?php

namespace App\Services;

use App\Models\{Application, Bill, Complaint, Consumer, Meter, Subdivision, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardService
{
    /**
     * Get all dashboard statistics with caching.
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'subdivisions' => Subdivision::count(),
                'consumers' => Consumer::count(),
                'meters' => $this->getMeterStats(),
                'revenue' => $this->getRevenueStats(),
                'power_loss' => $this->getPowerLossStats(),
                'complaints' => $this->getComplaintStats(),
                'users' => User::count(),
                'sdo_users' => User::where('role', 'ls')->count(),
            ];
        });
    }

    /**
     * Get meter statistics.
     */
    private function getMeterStats(): array
    {
        return [
            'total' => Meter::count(),
            'active' => Meter::where('status', 'active')->count(),
            'faulty' => Meter::where('status', 'faulty')->count(),
            'disconnected' => Meter::where('status', 'disconnected')->count(),
        ];
    }

    /**
     * Get revenue statistics.
     */
    private function getRevenueStats(): array
    {
        $currentMonth = date('F');
        $currentYear = date('Y');

        return [
            'this_month' => Bill::where('billing_month', $currentMonth)
                ->where('billing_year', $currentYear)
                ->sum('amount_paid'),
            'total' => Bill::where('payment_status', 'paid')->sum('amount_paid'),
            'pending' => Bill::whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
        ];
    }

    /**
     * Get power loss statistics.
     */
    private function getPowerLossStats(): array
    {
        $totalSupplied = Bill::sum('units_consumed');
        $totalBilled = Bill::sum('units_consumed');
        
        return [
            'total_supplied' => $totalSupplied,
            'total_billed' => $totalBilled,
            'loss_percentage' => $totalSupplied > 0 
                ? round((($totalSupplied - $totalBilled) / $totalSupplied) * 100, 2) 
                : 0,
        ];
    }

    /**
     * Get complaint statistics.
     */
    private function getComplaintStats(): array
    {
        return [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
        ];
    }

    /**
     * Get revenue trend for the last 6 months.
     */
    public function getRevenueTrend(): \Illuminate\Support\Collection
    {
        return Bill::select(
                DB::raw('DATE_FORMAT(issue_date, "%Y-%m") as month'),
                DB::raw('SUM(amount_paid) as revenue')
            )
            ->where('issue_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get complaint trend for the last 30 days.
     */
    public function getComplaintTrend(): \Illuminate\Support\Collection
    {
        return Complaint::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get subdivision statistics with proper eager loading to avoid N+1.
     */
    public function getSubdivisionStats(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Subdivision::withCount(['consumers', 'meters', 'bills', 'complaints'])
            ->with(['bills' => function($query) {
                $query->select('subdivision_id', DB::raw('SUM(amount_paid) as total_revenue'))
                    ->groupBy('subdivision_id');
            }])
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent applications with eager loading.
     */
    public function getRecentApplications(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Application::with(['company:id,name', 'subdivision:id,name'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent complaints with eager loading.
     */
    public function getRecentComplaints(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return Complaint::with(['consumer:id,name', 'subdivision:id,name'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent users.
     */
    public function getRecentUsers(int $limit = 6): \Illuminate\Support\Collection
    {
        return User::select('id', 'name', 'email', 'role', 'created_at')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get application statistics grouped by status.
     */
    public function getApplicationStats(): array
    {
        return Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get bill statistics.
     */
    public function getBillStats(): array
    {
        return [
            'total_bills' => Bill::count(),
            'paid' => Bill::where('payment_status', 'paid')->count(),
            'unpaid' => Bill::where('payment_status', 'unpaid')->count(),
            'overdue' => Bill::where('payment_status', 'overdue')->count(),
        ];
    }
}
