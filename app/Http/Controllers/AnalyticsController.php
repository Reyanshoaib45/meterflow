<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Consumer;
use App\Models\Meter;
use App\Models\Subdivision;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard.
     */
    public function index()
    {
        return view('admin.analytics.index');
    }

    /**
     * Revenue vs Energy Supplied Report.
     */
    public function revenueReport(Request $request)
    {
        $query = Bill::with('subdivision')
            ->select(
                'subdivision_id',
                DB::raw('SUM(units_consumed) as total_units'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(amount_paid) as total_paid'),
                DB::raw('COUNT(*) as total_bills')
            )
            ->groupBy('subdivision_id');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('issue_date', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();
        $subdivisions = Subdivision::orderBy('name')->get();

        return view('admin.analytics.revenue', compact('data', 'subdivisions'));
    }

    /**
     * Complaint Response Time Report.
     */
    public function complaintReport(Request $request)
    {
        $query = Complaint::with('subdivision')
            ->select(
                'subdivision_id',
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_resolution_hours')
            )
            ->whereNotNull('resolved_at')
            ->groupBy('subdivision_id', 'status');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();
        $subdivisions = Subdivision::orderBy('name')->get();

        // Overall stats
        $overallStats = [
            'total_complaints' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'avg_response_time' => Complaint::whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
                ->value('avg_hours'),
        ];

        return view('admin.analytics.complaints', compact('data', 'subdivisions', 'overallStats'));
    }

    /**
     * Faulty Meters Trend Report.
     */
    public function faultyMetersReport(Request $request)
    {
        $query = Meter::with('subdivision')
            ->select(
                'subdivision_id',
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('subdivision_id', 'status');

        $data = $query->get();
        $subdivisions = Subdivision::orderBy('name')->get();

        // Overall meter stats
        $meterStats = [
            'total' => Meter::count(),
            'active' => Meter::where('status', 'active')->count(),
            'faulty' => Meter::where('status', 'faulty')->count(),
            'disconnected' => Meter::where('status', 'disconnected')->count(),
        ];

        return view('admin.analytics.faulty-meters', compact('data', 'subdivisions', 'meterStats'));
    }

    /**
     * Collection Efficiency Report.
     */
    public function collectionReport(Request $request)
    {
        $query = Bill::with('subdivision')
            ->select(
                'subdivision_id',
                'billing_month',
                'billing_year',
                DB::raw('SUM(total_amount) as total_billed'),
                DB::raw('SUM(amount_paid) as total_collected'),
                DB::raw('(SUM(amount_paid) / SUM(total_amount) * 100) as collection_efficiency')
            )
            ->groupBy('subdivision_id', 'billing_month', 'billing_year')
            ->orderBy('billing_year', 'desc')
            ->orderBy('billing_month', 'desc');

        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $data = $query->paginate(20);
        $subdivisions = Subdivision::orderBy('name')->get();

        return view('admin.analytics.collection', compact('data', 'subdivisions'));
    }

    /**
     * High Loss Areas Report.
     */
    public function highLossReport(Request $request)
    {
        $query = Bill::with('subdivision')
            ->select(
                'subdivision_id',
                DB::raw('SUM(units_consumed) as total_units_billed'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(total_amount - amount_paid) as total_loss'),
                DB::raw('COUNT(*) as total_bills'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "overdue") THEN 1 ELSE 0 END) as unpaid_bills')
            )
            ->groupBy('subdivision_id')
            ->orderByDesc('total_loss')
            ->limit(10);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('issue_date', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        return view('admin.analytics.high-loss', compact('data'));
    }

    /**
     * Monthly Revenue Trend.
     */
    public function revenueTrend(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $data = Bill::select(
                'billing_month',
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(amount_paid) as collected_revenue'),
                DB::raw('COUNT(*) as bill_count')
            )
            ->where('billing_year', $year)
            ->groupBy('billing_month')
            ->orderByRaw("FIELD(billing_month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();

        return view('admin.analytics.revenue-trend', compact('data', 'year'));
    }

    /**
     * Export report to PDF.
     */
    public function exportReport(Request $request)
    {
        $reportType = $request->input('type');
        
        // Here you would implement PDF generation logic
        // For now, returning a simple response
        
        return response()->json([
            'message' => 'Report export functionality will be implemented',
            'type' => $reportType,
        ]);
    }

    /**
     * Dashboard statistics API.
     */
    public function dashboardStats()
    {
        $stats = [
            'consumers' => [
                'total' => Consumer::count(),
                'active' => Consumer::where('status', 'active')->count(),
                'disconnected' => Consumer::where('status', 'disconnected')->count(),
            ],
            'meters' => [
                'total' => Meter::count(),
                'active' => Meter::where('status', 'active')->count(),
                'faulty' => Meter::where('status', 'faulty')->count(),
                'disconnected' => Meter::where('status', 'disconnected')->count(),
            ],
            'bills' => [
                'total' => Bill::count(),
                'paid' => Bill::where('payment_status', 'paid')->count(),
                'unpaid' => Bill::where('payment_status', 'unpaid')->count(),
                'overdue' => Bill::where('payment_status', 'overdue')->count(),
            ],
            'complaints' => [
                'total' => Complaint::count(),
                'pending' => Complaint::where('status', 'pending')->count(),
                'in_progress' => Complaint::where('status', 'in_progress')->count(),
                'resolved' => Complaint::where('status', 'resolved')->count(),
            ],
            'revenue' => [
                'this_month' => Bill::where('billing_month', date('F'))
                    ->where('billing_year', date('Y'))
                    ->sum('amount_paid'),
                'total' => Bill::where('payment_status', 'paid')->sum('amount_paid'),
                'pending' => Bill::whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
            ],
            'subdivisions' => Subdivision::count(),
        ];

        return response()->json($stats);
    }
}
