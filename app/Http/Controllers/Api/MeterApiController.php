<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meter;
use App\Models\Subdivision;
use Illuminate\Support\Facades\Auth;

class MeterApiController extends Controller
{
    /**
     * Get all meters.
     */
    public function index(Request $request)
    {
        $query = Meter::with(['consumer', 'subdivision', 'application']);

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
                $q->where('meter_no', 'like', "%{$search}%")
                  ->orWhere('meter_make', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $meters = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $meters,
        ]);
    }

    /**
     * Get single meter.
     */
    public function show($id)
    {
        $meter = Meter::with(['consumer', 'subdivision', 'application', 'bills'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $meter,
        ]);
    }

    /**
     * Check meter number availability.
     */
    public function checkMeterNumber(Request $request)
    {
        $request->validate([
            'meter_no' => 'required|string',
        ]);

        $meter = Meter::where('meter_no', $request->meter_no)->first();

        if ($meter) {
            return response()->json([
                'success' => true,
                'exists' => true,
                'message' => 'Meter number exists',
                'data' => [
                    'meter' => $meter->load('consumer'),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'exists' => false,
            'message' => 'Meter number not found',
        ]);
    }

    /**
     * Create new meter.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meter_no' => 'required|string|unique:meters',
            'meter_make' => 'nullable|string',
            'consumer_id' => 'nullable|exists:consumers,id',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'installed_on' => 'nullable|date',
            'status' => 'nullable|in:active,faulty,disconnected',
        ]);

        $validated['in_store'] = true;

        $meter = Meter::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meter created successfully',
            'data' => $meter->load(['consumer', 'subdivision']),
        ], 201);
    }

    /**
     * Update meter.
     */
    public function update(Request $request, $id)
    {
        $meter = Meter::findOrFail($id);

        $validated = $request->validate([
            'meter_make' => 'nullable|string',
            'reading' => 'nullable|numeric',
            'sim_number' => 'nullable|string',
            'status' => 'nullable|in:active,faulty,disconnected',
        ]);

        $meter->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meter updated successfully',
            'data' => $meter->fresh(['consumer', 'subdivision']),
        ]);
    }

    /**
     * Delete meter.
     */
    public function destroy($id)
    {
        $meter = Meter::findOrFail($id);
        $meter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meter deleted successfully',
        ]);
    }
}
