<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ApplicationHistory;
use App\Models\Application;

class UserPanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get applications directly by user_id
        $applications = Application::with(['company', 'subdivision'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Get activities for this user's applications
        $applicationIds = $applications->pluck('id');
        
        $activities = ApplicationHistory::with(['application'])
            ->where(function($query) use ($applicationIds, $user) {
                $query->whereIn('application_id', $applicationIds)
                      ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('user.panel', compact('user', 'activities', 'applications'));
    }
}
