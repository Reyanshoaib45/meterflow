<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Company;
use App\Models\Subdivision;
use Illuminate\Support\Facades\Auth;

class ApplicationApiController extends Controller
{
    /**
     * Get all applications.
     */
    public function index(Request $request)
    {
        $query = Application::with(['company', 'subdivision', 'meter']);

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $applications = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $applications,
        ]);
    }

    /**
     * Create new application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_no' => 'required|string|max:50|unique:applications,application_no',
            'customer_name' => 'required|string|max:200',
            'customer_cnic' => 'nullable|string|max:30',
            'phone' => 'required|string|regex:/^[0-9]{11}$/',
            'address' => 'nullable|string',
            'company_id' => 'required|exists:companies,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'meter_number' => 'nullable|string|max:100',
            'connection_type' => 'nullable|string|max:50',
        ]);

        $validated['status'] = 'pending';
        $validated['cnic'] = $validated['customer_cnic'] ?? null;
        unset($validated['customer_cnic']);

        if (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        $application = Application::create($validated);

        // Create history
        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'submitted',
            'remarks' => 'Application submitted via API',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application created successfully',
            'data' => $application->load(['company', 'subdivision']),
        ], 201);
    }

    /**
     * Get single application.
     */
    public function show($id)
    {
        $application = Application::with(['company', 'subdivision', 'meter', 'histories'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $application,
        ]);
    }

    /**
     * Update application.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'sometimes|required|string|max:200',
            'customer_cnic' => 'nullable|string|max:30',
            'phone' => 'sometimes|required|string|regex:/^[0-9]{11}$/',
            'address' => 'nullable|string',
            'company_id' => 'sometimes|required|exists:companies,id',
            'subdivision_id' => 'sometimes|required|exists:subdivisions,id',
            'meter_number' => 'nullable|string|max:100',
            'connection_type' => 'nullable|string|max:50',
            'status' => 'nullable|in:pending,approved,rejected,closed',
        ]);

        if (array_key_exists('customer_cnic', $validated)) {
            $validated['cnic'] = $validated['customer_cnic'];
            unset($validated['customer_cnic']);
        }

        $oldValues = $application->getOriginal();
        $application->update($validated);

        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'updated',
            'remarks' => 'Application updated via API',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application updated successfully',
            'data' => $application->fresh(['company', 'subdivision']),
        ]);
    }

    /**
     * Delete application.
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Application deleted successfully',
        ]);
    }

    /**
     * Track application by application number.
     */
    public function track(Request $request)
    {
        $request->validate([
            'application_no' => 'required|string',
        ]);

        $application = Application::with(['company', 'subdivision', 'histories'])
            ->where('application_no', $request->application_no)
            ->first();

        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $application,
        ]);
    }

    /**
     * Update application status (Admin only).
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,closed',
            'remarks' => 'nullable|string',
        ]);

        $application = Application::findOrFail($id);
        $oldStatus = $application->status;
        
        $application->update(['status' => $validated['status']]);

        ApplicationHistory::create([
            'application_id' => $application->id,
            'subdivision_id' => $application->subdivision_id,
            'company_id' => $application->company_id,
            'action_type' => 'status_changed',
            'remarks' => $validated['remarks'] ?? "Status changed from {$oldStatus} to {$validated['status']}",
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated',
            'data' => $application->fresh(['company', 'subdivision']),
        ]);
    }

    /**
     * Get companies list.
     */
    public function companies()
    {
        $companies = Company::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }

    /**
     * Get subdivisions list.
     */
    public function subdivisions(Request $request)
    {
        $query = Subdivision::with('company')->orderBy('name');

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $subdivisions = $query->get();

        return response()->json([
            'success' => true,
            'data' => $subdivisions,
        ]);
    }
}
