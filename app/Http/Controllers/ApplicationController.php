<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function track()
    {
        return view('track'); // create track view separately
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_no' => 'required|string|max:50|unique:applications,application_no',
            'customer_name'  => 'required|string|max:200',
            'customer_cnic'  => 'nullable|string|max:30',
            'phone'          => 'nullable|string|max:30',
            'address'        => 'nullable|string',
            'company_id'     => 'nullable|exists:companies,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'meter_number'   => 'nullable|string|max:100',
            'connection_type'=> 'nullable|string|max:50',
        ]);

        // $app = Application::create($validated);

        // // create initial history (if you have application_logs model)
        // if (class_exists(\App\Models\ApplicationLog::class)) {
        //     \App\Models\ApplicationLog::create([
        //         'application_id' => $app->id,
        //         'user_id' => null,
        //         'action' => 'Submitted',
        //         'remarks' => null,
        //     ]);
        // }

        return redirect()->route('landing')->with('success','Application submitted successfully. Your application number: '.$app->application_no);
    }
}