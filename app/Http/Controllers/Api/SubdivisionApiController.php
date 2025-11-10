<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subdivision;

class SubdivisionApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Subdivision::with('company')->orderBy('name');
        
        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        $subs = $query->paginate($request->input('per_page', 50));
        
        return response()->json([
            'success' => true,
            'data' => $subs,
        ]);
    }
    
    public function show($id)
    {
        $subdivision = Subdivision::with('company')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $subdivision,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subdivisions,code',
            'company_id' => 'required|exists:companies,id',
            'subdivision_message' => 'nullable|string',
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        $subdivision = Subdivision::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Subdivision created successfully',
            'data' => $subdivision->load('company'),
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        $subdivision = Subdivision::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50|unique:subdivisions,code,' . $subdivision->id,
            'company_id' => 'sometimes|required|exists:companies,id',
            'subdivision_message' => 'nullable|string',
            'ls_id' => 'nullable|exists:users,id',
        ]);
        
        $subdivision->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Subdivision updated successfully',
            'data' => $subdivision->fresh('company'),
        ]);
    }
    
    public function destroy($id)
    {
        $subdivision = Subdivision::findOrFail($id);
        $subdivision->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Subdivision deleted successfully',
        ]);
    }
}
*** End Patch !***  }``` -->

