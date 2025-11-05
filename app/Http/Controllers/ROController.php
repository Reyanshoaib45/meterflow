<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subdivision;
use App\Models\User;
use App\Traits\LogsActivity;

class ROController extends Controller
{
    use LogsActivity;

        /**
     * Show subdivision selection page for RO login.
     */
    public function selectSubdivision()
    {
        // Show all subdivisions that have RO users assigned via pivot table    
        $subdivisions = Subdivision::with(['company'])
            ->whereHas('users', function($query) {
                $query->where('users.role', 'ro');
            })
            ->withCount(['applications', 'meters'])
            ->orderBy('name')
            ->get();
        
        return view('Ls.select-subdivision', compact('subdivisions'));
    }

    /**
     * Show RO login page for specific subdivision.
     */
    public function showLogin($subdivisionId)
    {
        // Check if subdivision has RO users assigned
        $subdivision = Subdivision::with('company')
            ->whereHas('users', function($query) {
                $query->where('users.role', 'ro');
            })
            ->findOrFail($subdivisionId);
        
        return view('Ls.login', compact('subdivision'));
    }

    /**
     * Authenticate RO user.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'subdivision_id' => 'required|exists:subdivisions,id',
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])
            ->where('role', 'ro')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');
        }

        // Check if user is assigned to this subdivision via pivot table
        $subdivision = Subdivision::where('id', $credentials['subdivision_id'])
            ->whereHas('users', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        if (!$subdivision) {
            return back()->withErrors([
                'username' => 'You are not authorized to access this subdivision.',
            ])->onlyInput('username');
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        // Store subdivision in session
        session(['current_subdivision_id' => $subdivision->id]);

        // Log activity
        self::logActivity('RO Login', 'Logged In', 'Subdivision', $subdivision->id, null, [
            'subdivision' => $subdivision->name,
        ]);

        return redirect()->route('ro.dashboard');
    }
}
