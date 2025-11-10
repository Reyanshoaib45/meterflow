<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tariff;

class TariffApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Tariff::query();
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $tariffs = $query->orderBy('category')->paginate($request->input('per_page', 50));
        
        return response()->json([
            'success' => true,
            'data' => $tariffs,
        ]);
    }
    
    public function show($id)
    {
        $tariff = Tariff::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $tariff,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'rate' => 'required|numeric',
            'fixed_charges' => 'nullable|numeric',
        ]);
        
        $tariff = Tariff::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tariff created successfully',
            'data' => $tariff,
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        $tariff = Tariff::findOrFail($id);
        
        $validated = $request->validate([
            'category' => 'sometimes|required|string|max:100',
            'rate' => 'sometimes|required|numeric',
            'fixed_charges' => 'nullable|numeric',
        ]);
        
        $tariff->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Tariff updated successfully',
            'data' => $tariff,
        ]);
    }
    
    public function destroy($id)
    {
        $tariff = Tariff::findOrFail($id);
        $tariff->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Tariff deleted successfully',
        ]);
    }
}
*** End Patch ***!

