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

        $activities = ApplicationHistory::with(['application'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $applicationIds = $activities->pluck('application_id')->filter()->unique()->values();

        $applications = Application::with(['company', 'subdivision'])
            ->whereIn('id', $applicationIds)
            ->orderByDesc('id')
            ->get();

        return view('user.panel', compact('user', 'activities', 'applications'));
    }
}
