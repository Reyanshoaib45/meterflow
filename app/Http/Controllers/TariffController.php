<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tariff;
use App\Traits\LogsActivity;

class TariffController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of tariffs.
     */
    public function index()
    {
        // Use 27 records for initial load, 15 for subsequent pages
        $perPage = request()->get('page', 1) == 1 ? 27 : 15;
        $tariffs = Tariff::orderBy('category')
            ->orderBy('from_units')
            ->paginate($perPage);
        
        return view('admin.tariffs.index', compact('tariffs'));
    }

    /**
     * Show the form for creating a new tariff.
     */
    public function create()
    {
        return view('admin.tariffs.create');
    }

    /**
     * Store a newly created tariff.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'from_units' => 'required|integer|min:0',
            'to_units' => 'nullable|integer|gt:from_units',
            'rate_per_unit' => 'required|numeric|min:0',
            'fixed_charges' => 'nullable|numeric|min:0',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'tv_fee' => 'nullable|numeric|min:0',
            'meter_rent' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        $tariff = Tariff::create($validated);

        self::logActivity('Tariffs', 'Created', 'Tariff', $tariff->id, null, $validated);

        return redirect()->route('admin.tariffs.index')
            ->with('success', 'Tariff created successfully.');
    }

    /**
     * Display the specified tariff.
     */
    public function show(Tariff $tariff)
    {
        return view('admin.tariffs.show', compact('tariff'));
    }

    /**
     * Show the form for editing the specified tariff.
     */
    public function edit(Tariff $tariff)
    {
        return view('admin.tariffs.edit', compact('tariff'));
    }

    /**
     * Update the specified tariff.
     */
    public function update(Request $request, Tariff $tariff)
    {
        $oldValues = $tariff->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'from_units' => 'required|integer|min:0',
            'to_units' => 'nullable|integer|gt:from_units',
            'rate_per_unit' => 'required|numeric|min:0',
            'fixed_charges' => 'nullable|numeric|min:0',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'tv_fee' => 'nullable|numeric|min:0',
            'meter_rent' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        $tariff->update($validated);

        self::logActivity('Tariffs', 'Updated', 'Tariff', $tariff->id, $oldValues, $validated);

        return redirect()->route('admin.tariffs.index')
            ->with('success', 'Tariff updated successfully.');
    }

    /**
     * Remove the specified tariff.
     */
    public function destroy(Tariff $tariff)
    {
        $oldValues = $tariff->toArray();
        $tariff->delete();

        self::logActivity('Tariffs', 'Deleted', 'Tariff', $tariff->id, $oldValues, null);

        return redirect()->route('admin.tariffs.index')
            ->with('success', 'Tariff deleted successfully.');
    }

    /**
     * Toggle tariff active status.
     */
    public function toggleStatus(Tariff $tariff)
    {
        $oldStatus = $tariff->is_active;
        $tariff->update(['is_active' => !$tariff->is_active]);

        self::logActivity('Tariffs', 'Status Toggled', 'Tariff', $tariff->id, 
            ['is_active' => $oldStatus], 
            ['is_active' => $tariff->is_active]
        );

        return redirect()->back()
            ->with('success', 'Tariff status updated successfully.');
    }
}
