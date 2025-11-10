<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Support\Facades\Auth;

class ComplaintApiController extends Controller
{
    /**
     * Get all complaints.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['consumer', 'subdivision', 'assignedUser']);

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('complaint_id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $complaints = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $complaints,
        ]);
    }

    /**
     * Get single complaint.
     */
    public function show($id)
    {
        $complaint = Complaint::with(['consumer', 'subdivision', 'assignedUser', 'histories'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $complaint,
        ]);
    }

    /**
     * Create new complaint.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'consumer_ref' => 'nullable|string|max:255',
            'complaint_type' => 'required|string|in:billing,power_outage,meter,service,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:normal,high,urgent',
        ]);

        $complaintId = 'CMP-' . strtoupper(uniqid());

        $complaint = Complaint::create([
            'complaint_id' => $complaintId,
            'company_id' => $validated['company_id'],
            'subdivision_id' => $validated['subdivision_id'],
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'consumer_ref' => $validated['consumer_ref'] ?? null,
            'complaint_type' => $validated['complaint_type'],
            'type' => $validated['complaint_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'action_type' => 'created',
            'description' => 'Complaint filed via API',
            'status_from' => null,
            'status_to' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint submitted successfully',
            'data' => $complaint->load(['subdivision']),
        ], 201);
    }

    /**
     * Update complaint.
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'consumer_ref' => 'nullable|string|max:255',
            'complaint_type' => 'sometimes|required|string|in:billing,power_outage,meter,service,other',
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|string|in:normal,high,urgent',
            'status' => 'nullable|string|in:pending,in_progress,resolved,closed',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $complaint->status;
        $complaint->update([
            'customer_name' => $validated['customer_name'] ?? $complaint->customer_name,
            'phone' => $validated['phone'] ?? $complaint->phone,
            'consumer_ref' => $validated['consumer_ref'] ?? $complaint->consumer_ref,
            'complaint_type' => $validated['complaint_type'] ?? $complaint->complaint_type,
            'type' => $validated['complaint_type'] ?? $complaint->type,
            'subject' => $validated['subject'] ?? $complaint->subject,
            'description' => $validated['description'] ?? $complaint->description,
            'priority' => $validated['priority'] ?? $complaint->priority,
            'status' => $validated['status'] ?? $complaint->status,
            'assigned_user_id' => $validated['assigned_user_id'] ?? $complaint->assigned_user_id,
        ]);

        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'action_type' => 'updated',
            'description' => 'Complaint updated via API',
            'status_from' => $oldStatus,
            'status_to' => $complaint->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint updated successfully',
            'data' => $complaint->fresh(),
        ]);
    }

    /**
     * Delete complaint.
     */
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Complaint deleted successfully',
        ]);
    }

    /**
     * Track complaint by complaint ID.
     */
    public function track(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|string',
        ]);

        $complaint = Complaint::with(['subdivision', 'histories'])
            ->where('complaint_id', $request->complaint_id)
            ->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Complaint not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $complaint,
        ]);
    }
}
