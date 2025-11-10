<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;

class BillingApiController extends Controller
{
    /**
     * Get all bills.
     */
    public function index(Request $request)
    {
        $query = Bill::with(['consumer', 'meter', 'subdivision']);

        // Filters
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->has('consumer_id')) {
            $query->where('consumer_id', $request->consumer_id);
        }

        if ($request->has('month')) {
            $query->where('billing_month', $request->month);
        }

        if ($request->has('year')) {
            $query->where('billing_year', $request->year);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_no', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $bills = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $bills,
        ]);
    }

    /**
     * Get single bill.
     */
    public function show($id)
    {
        $bill = Bill::with(['consumer', 'meter', 'subdivision'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bill,
        ]);
    }

    /**
     * Get bill by bill number.
     */
    public function getByBillNumber(Request $request)
    {
        $request->validate([
            'bill_no' => 'required|string',
        ]);

        $bill = Bill::with(['consumer', 'meter', 'subdivision'])
            ->where('bill_no', $request->bill_no)
            ->first();

        if (!$bill) {
            return response()->json([
                'success' => false,
                'message' => 'Bill not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bill,
        ]);
    }

    /**
     * Get billing statistics.
     */
    public function statistics(Request $request)
    {
        $query = Bill::query();

        if ($request->has('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $stats = [
            'total_bills' => (clone $query)->count(),
            'paid_bills' => (clone $query)->where('payment_status', 'paid')->count(),
            'unpaid_bills' => (clone $query)->where('payment_status', 'unpaid')->count(),
            'overdue_bills' => (clone $query)->where('payment_status', 'overdue')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'paid')->sum('amount_paid'),
            'pending_amount' => (clone $query)->whereIn('payment_status', ['unpaid', 'overdue'])->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
