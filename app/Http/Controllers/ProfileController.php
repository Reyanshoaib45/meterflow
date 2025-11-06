<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Load subdivisions based on user role
        $subdivisions = collect();
        if ($user->role === 'ls') {
            // LS users: subdivisions assigned via ls_id
            $subdivisions = \App\Models\Subdivision::where('ls_id', $user->id)
                ->with('company')
                ->get();
        } elseif (in_array($user->role, ['sdc', 'ro'])) {
            // SDC/RO users: subdivisions assigned via pivot table
            $subdivisions = $user->subdivisions()->with('company')->get();
        }
        
        return view('profile.edit', [
            'user' => $user,
            'subdivisions' => $subdivisions,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
