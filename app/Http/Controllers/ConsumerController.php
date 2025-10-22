<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consumer;
use App\Models\Subdivision;
use App\Traits\LogsActivity;

class ConsumerController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of consumers.
     */
    public function index(Request $request)
    {
        $query = Consumer::with('subdivision');

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%")
                  ->orWhere('consumer_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->filled('connection_type')) {
            $query->where('connection_type', $request->connection_type);
        }

        $consumers = $query->latest()->paginate(20);
        $subdivisions = Subdivision::orderBy('name')->get();

        return view('admin.consumers.index', compact('consumers', 'subdivisions'));
    }

    /**
     * Show the form for creating a new consumer.
     */
    public function create()
    {
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.consumers.create', compact('subdivisions'));
    }

    /**
     * Store a newly created consumer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|unique:consumers|size:13',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'connection_type' => 'required|in:residential,commercial,industrial',
            'status' => 'required|in:active,disconnected,suspended',
        ]);

        // Generate consumer ID
        $consumerId = 'CON-' . date('Ym') . '-' . str_pad(Consumer::count() + 1, 6, '0', STR_PAD_LEFT);
        $validated['consumer_id'] = $consumerId;

        $consumer = Consumer::create($validated);

        self::logActivity('Consumers', 'Created', 'Consumer', $consumer->id, null, $validated);

        return redirect()->route('admin.consumers.index')
            ->with('success', 'Consumer created successfully.');
    }

    /**
     * Display the specified consumer.
     */
    public function show(Consumer $consumer)
    {
        $consumer->load(['subdivision', 'meters', 'bills', 'complaints']);
        
        return view('admin.consumers.show', compact('consumer'));
    }

    /**
     * Show the form for editing the specified consumer.
     */
    public function edit(Consumer $consumer)
    {
        $subdivisions = Subdivision::orderBy('name')->get();
        
        return view('admin.consumers.edit', compact('consumer', 'subdivisions'));
    }

    /**
     * Update the specified consumer.
     */
    public function update(Request $request, Consumer $consumer)
    {
        $oldValues = $consumer->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|size:13|unique:consumers,cnic,' . $consumer->id,
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'connection_type' => 'required|in:residential,commercial,industrial',
            'status' => 'required|in:active,disconnected,suspended',
        ]);

        $consumer->update($validated);

        self::logActivity('Consumers', 'Updated', 'Consumer', $consumer->id, $oldValues, $validated);

        return redirect()->route('admin.consumers.index')
            ->with('success', 'Consumer updated successfully.');
    }

    /**
     * Remove the specified consumer.
     */
    public function destroy(Consumer $consumer)
    {
        // Check if consumer has active meters or bills
        if ($consumer->meters()->count() > 0) {
            return redirect()->route('admin.consumers.index')
                ->with('error', 'Cannot delete consumer with existing meters.');
        }

        $oldValues = $consumer->toArray();
        $consumer->delete();

        self::logActivity('Consumers', 'Deleted', 'Consumer', $consumer->id, $oldValues, null);

        return redirect()->route('admin.consumers.index')
            ->with('success', 'Consumer deleted successfully.');
    }
    
    /**
     * Show consumer history (bills and complaints).
     */
    public function history(Consumer $consumer)
    {
        $consumer->load('subdivision');
        $bills = \App\Models\Bill::where('consumer_id', $consumer->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $complaints = \App\Models\Complaint::where('consumer_id', $consumer->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.consumers.history', compact('consumer', 'bills', 'complaints'));
    }
    
    /**
     * Show consumer applications.
     */
    public function applications(Consumer $consumer)
    {
        $consumer->load('subdivision');
        
        // Find applications by matching CNIC
        $applications = \App\Models\Application::where('cnic', $consumer->cnic)
            ->orWhere('customer_name', $consumer->name)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.consumers.applications', compact('consumer', 'applications'));
    }
}
