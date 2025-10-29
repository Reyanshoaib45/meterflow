<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    public function set(Request $request)
    {
        $value = $request->boolean('value', false);
        Cache::put('custom_maintenance_enabled', $value, now()->addDays(7));
        return response()->json(['maintenance' => $value]);
    }

    public function status()
    {
        return response()->json([
            'maintenance' => Cache::get('custom_maintenance_enabled', false)
        ]);
    }
}
