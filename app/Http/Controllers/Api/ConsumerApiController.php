<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Consumer;

class ConsumerApiController extends Controller
{
    /**
     * Get all consumers.
     */
    public function index(Request $request)
    {
        $query = Consumer::with('subdivision');

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('subdivision_id')) {
            $query->where('subdivision_id', $request->subdivision_id);
        }

        if ($request->has('connection_type')) {
            $query->where('connection_type', $request->connection_type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%")
                  ->orWhere('consumer_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);
        $consumers = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $consumers,
        ]);
    }

    /**
     * Get single consumer.
     */
    public function show($id)
    {
        $consumer = Consumer::with(['subdivision', 'meters', 'bills', 'complaints'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $consumer,
        ]);
    }

    /**
     * Search consumer by CNIC.
     */
    public function findByCnic(Request $request)
    {
        $request->validate([
            'cnic' => 'required|string|size:13',
        ]);

        $consumer = Consumer::with(['subdivision', 'meters'])
            ->where('cnic', $request->cnic)
            ->first();

        if (!$consumer) {
            return response()->json([
                'success' => false,
                'message' => 'Consumer not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $consumer,
        ]);
    }

    /**
     * Get consumer history (bills and complaints).
     */
    public function history($id)
    {
        $consumer = Consumer::with([
            'bills' => function($query) {
                $query->latest()->limit(20);
            },
            'complaints' => function($query) {
                $query->latest()->limit(20);
            },
            'subdivision'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'consumer' => $consumer,
                'bills' => $consumer->bills,
                'complaints' => $consumer->complaints,
            ],
        ]);
    }

    /**
     * Create consumer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cnic' => 'required|string|size:13|unique:consumers,cnic',
            'consumer_id' => 'required|string|max:50|unique:consumers,consumer_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'subdivision_id' => 'required|exists:subdivisions,id',
            'connection_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $consumer = Consumer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Consumer created successfully',
            'data' => $consumer->load('subdivision'),
        ], 201);
    }

    /**
     * Update consumer.
     */
    public function update(Request $request, $id)
    {
        $consumer = Consumer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'cnic' => 'sometimes|required|string|size:13|unique:consumers,cnic,' . $consumer->id,
            'consumer_id' => 'sometimes|required|string|max:50|unique:consumers,consumer_id,' . $consumer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'subdivision_id' => 'sometimes|required|exists:subdivisions,id',
            'connection_type' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $consumer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Consumer updated successfully',
            'data' => $consumer->fresh('subdivision'),
        ]);
    }

    /**
     * Delete consumer.
     */
    public function destroy($id)
    {
        $consumer = Consumer::findOrFail($id);
        $consumer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Consumer deleted successfully',
        ]);
    }
}
