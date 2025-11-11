<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = [
            'maintenance_mode' => Setting::get('maintenance_mode', '0'),
            'website_speed' => Setting::get('website_speed', 'high'),
            'show_maintenance_below_loading' => Setting::get('show_maintenance_below_loading', '0'),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'website_speed' => 'required|in:low,medium,high',
        ]);

        // Handle checkboxes (they don't send value if unchecked)
        $maintenanceMode = $request->has('maintenance_mode') ? '1' : '0';
        $showMaintenanceBelowLoading = $request->has('show_maintenance_below_loading') ? '1' : '0';

        Setting::set('website_speed', $validated['website_speed']);
        Setting::set('maintenance_mode', $maintenanceMode);
        Setting::set('show_maintenance_below_loading', $showMaintenanceBelowLoading);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
}
