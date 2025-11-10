<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyApiController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::orderBy('name')->paginate($request->input('per_page', 50));
        
        return response()->json([
            'success' => true,
            'data' => $companies,
        ]);
    }
    
    public function show($id)
    {
        $company = Company::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $company,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code',
        ]);
        
        $company = Company::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Company created successfully',
            'data' => $company,
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50|unique:companies,code,' . $company->id,
        ]);
        
        $company->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully',
            'data' => $company,
        ]);
    }
    
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully',
        ]);
    }
}
*** End Patch ***!

