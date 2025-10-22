<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::get('/', function(){
    // You can pass companies/subdivisions for selects (fetch from DB)
    $companies = \App\Models\Company::orderBy('name')->get();
    $subdivisions = \App\Models\Subdivision::orderBy('name')->get();
    return view('landing', compact('companies','subdivisions'));
})->name('landing');

Route::get('/track', [ApplicationController::class,'track'])->name('track');

// store form
Route::post('/applications', [ApplicationController::class,'store'])->name('applications.store');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';