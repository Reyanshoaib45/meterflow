<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('active')) {
            $query->where('is_active', (bool) $request->active);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('name')->paginate($request->input('per_page', 50));
        
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user,ls,sdc,ro',
            'is_active' => 'nullable|boolean',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'sometimes|required|in:admin,user,ls,sdc,ro',
            'is_active' => 'nullable|boolean',
        ]);
        
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user->fresh(),
        ]);
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
*** End Patch ***!

