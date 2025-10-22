<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use App\Models\Consumer;
use App\Models\Subdivision;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of complaints.
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['consumer', 'subdivision', 'assignedUser']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('complaint_id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('consumer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $complaints = $query->latest()->paginate(20);
        $subdivisions = Subdivision::orderBy('name')->get();
        $sdoUsers = User::where('role', 'ls')->orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'subdivisions', 'sdoUsers', 'stats'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        $consumers = Consumer::where('status', 'active')->orderBy('name')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        $sdoUsers = User::where('role', 'ls')->orderBy('name')->get();
        
        return view('admin.complaints.create', compact('consumers', 'subdivisions', 'sdoUsers'));
    }

    /**
     * Store a newly created complaint.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_id' => 'required|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'type' => 'required|in:billing,meter_fault,power_outage,voltage_issue,connection,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Generate complaint ID
        $complaintId = 'CMP-' . date('Ymd') . '-' . str_pad(Complaint::count() + 1, 5, '0', STR_PAD_LEFT);

        $complaintData = array_merge($validated, [
            'complaint_id' => $complaintId,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        $complaint = Complaint::create($complaintData);

        // Create history entry
        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action' => 'Created',
            'comment' => 'Complaint created',
            'new_status' => 'pending',
        ]);

        self::logActivity('Complaints', 'Created', 'Complaint', $complaint->id, null, $complaintData);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint created successfully.');
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['consumer', 'subdivision', 'assignedUser', 'creator', 'histories.user']);
        
        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified complaint.
     */
    public function edit(Complaint $complaint)
    {
        $consumers = Consumer::orderBy('name')->get();
        $subdivisions = Subdivision::orderBy('name')->get();
        $sdoUsers = User::where('role', 'ls')->orderBy('name')->get();
        
        return view('admin.complaints.edit', compact('complaint', 'consumers', 'subdivisions', 'sdoUsers'));
    }

    /**
     * Update the specified complaint.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $oldValues = $complaint->toArray();
        $oldStatus = $complaint->status;

        $validated = $request->validate([
            'type' => 'required|in:billing,meter_fault,power_outage,voltage_issue,connection,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && $complaint->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $complaint->update($validated);

        // Create history entry if status changed
        if ($oldStatus !== $validated['status']) {
            ComplaintHistory::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::id(),
                'action' => 'Status Changed',
                'comment' => $request->input('comment', 'Status updated'),
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
            ]);
        }

        self::logActivity('Complaints', 'Updated', 'Complaint', $complaint->id, $oldValues, $validated);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Reassign complaint to another SDO.
     */
    public function reassign(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'comment' => 'nullable|string',
        ]);

        $oldAssignee = $complaint->assigned_to;
        $complaint->update(['assigned_to' => $validated['assigned_to']]);

        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action' => 'Reassigned',
            'comment' => $validated['comment'] ?? 'Complaint reassigned',
            'old_status' => $complaint->status,
            'new_status' => $complaint->status,
        ]);

        self::logActivity('Complaints', 'Reassigned', 'Complaint', $complaint->id, 
            ['assigned_to' => $oldAssignee], 
            ['assigned_to' => $validated['assigned_to']]
        );

        return redirect()->back()
            ->with('success', 'Complaint reassigned successfully.');
    }

    /**
     * Add comment to complaint.
     */
    public function addComment(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action' => 'Comment Added',
            'comment' => $validated['comment'],
            'old_status' => $complaint->status,
            'new_status' => $complaint->status,
        ]);

        return redirect()->back()
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Close complaint.
     */
    public function close(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $complaint->update([
            'status' => 'closed',
            'resolved_at' => now(),
            'resolution_notes' => $validated['resolution_notes'],
        ]);

        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'action' => 'Closed',
            'comment' => 'Complaint closed',
            'old_status' => $complaint->status,
            'new_status' => 'closed',
        ]);

        self::logActivity('Complaints', 'Closed', 'Complaint', $complaint->id, null, [
            'status' => 'closed',
            'resolution_notes' => $validated['resolution_notes'],
        ]);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint closed successfully.');
    }

    /**
     * Bulk reassign complaints.
     */
    public function bulkReassign(Request $request)
    {
        $validated = $request->validate([
            'complaint_ids' => 'required|array',
            'complaint_ids.*' => 'exists:complaints,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $complaints = Complaint::whereIn('id', $validated['complaint_ids'])->get();

        foreach ($complaints as $complaint) {
            $complaint->update(['assigned_to' => $validated['assigned_to']]);

            ComplaintHistory::create([
                'complaint_id' => $complaint->id,
                'user_id' => Auth::id(),
                'action' => 'Bulk Reassigned',
                'comment' => 'Bulk reassignment',
            ]);
        }

        self::logActivity('Complaints', 'Bulk Reassigned', null, null, null, [
            'count' => count($validated['complaint_ids']),
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()->back()
            ->with('success', count($validated['complaint_ids']) . ' complaints reassigned successfully.');
    }

    /**
     * Export complaints to CSV.
     */
    public function export(Request $request)
    {
        $query = Complaint::with(['consumer', 'subdivision', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        $complaints = $query->get();

        $filename = 'complaints_export_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($complaints) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Complaint ID', 'Consumer', 'Type', 'Subject', 'Priority', 'Status', 'Assigned To', 'Created', 'Resolved']);

            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->complaint_id,
                    $complaint->consumer->name ?? 'N/A',
                    ucfirst($complaint->type),
                    $complaint->subject,
                    ucfirst($complaint->priority),
                    ucfirst($complaint->status),
                    $complaint->assignedUser->name ?? 'Unassigned',
                    $complaint->created_at->format('Y-m-d H:i'),
                    $complaint->resolved_at ? $complaint->resolved_at->format('Y-m-d H:i') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
