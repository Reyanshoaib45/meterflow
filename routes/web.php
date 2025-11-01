<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GlobalSummaryController;
use App\Http\Controllers\LsController;
use App\Http\Controllers\SDCController;
use App\Http\Controllers\ROController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserPanelController;

Route::get('/', function(){
    if (!Auth::check()) {
        return view('auth.role-select');
    }
    $companies = \App\Models\Company::orderBy('name')->get();
    $subdivisions = \App\Models\Subdivision::orderBy('name')->get();
    return view('landing', compact('companies','subdivisions'));
})->name('landing');

// Custom maintenance routes (accessible when maintenance middleware is active)
Route::post('/maintenance/set', [MaintenanceController::class, 'set'])->name('maintenance.set');
Route::get('/maintenance/status', [MaintenanceController::class, 'status'])->name('maintenance.status');

Route::get('/track', [ApplicationController::class,'track'])->name('track');
// Sitemap (public)
Route::get('/sitemap.xml', function() {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
});
Route::post('/check-meter', [ApplicationController::class, 'checkMeter'])->name('check.meter');
Route::post('/check-application-number', [ApplicationController::class, 'checkApplicationNumber'])->name('check.application');
Route::get('/application/thanks/{application_no}', [ApplicationController::class, 'thanks'])->name('application.thanks');

// New Meter Request & Protected actions (auth required)
Route::middleware('auth')->group(function() {
    Route::get('/user-form', function(){
        $companies = \App\Models\Company::orderBy('name')->get();
        $subdivisions = \App\Models\Subdivision::orderBy('name')->get();
        return view('user-form', compact('companies','subdivisions'));
    })->name('user-form');

    Route::post('/applications', [ApplicationController::class,'store'])->name('applications.store');

    // Application actions
    Route::post('/application/{application_no}/close', [ApplicationController::class, 'close'])->name('application.close');
    Route::get('/application/{application_no}/invoice', [ApplicationController::class, 'generateInvoice'])->name('application.invoice');

    // Complaint routes (protected)
    Route::get('/file-complaint', function(){
        return view('file-complaint');
    })->name('file-complaint');
    Route::post('/store-complaint', [ComplaintController::class, 'storePublicComplaint'])->name('store-complaint');
});

// (Removed public complaint routes; now protected above)

// Static Pages
Route::get('/terms', function(){
    return view('static.terms');
})->name('terms');
Route::get('/privacy', function(){
    return view('static.privacy');
})->name('privacy');
Route::get('/about', function(){
    return view('static.about');
})->name('about');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Companies
        Route::get('/admin/companies', [AdminController::class, 'companies'])->name('admin.companies');
        Route::get('/admin/companies/create', [AdminController::class, 'createCompany'])->name('admin.companies.create');
        Route::post('/admin/companies', [AdminController::class, 'storeCompany'])->name('admin.companies.store');
        Route::get('/admin/companies/{company}/edit', [AdminController::class, 'editCompany'])->name('admin.companies.edit');
        Route::put('/admin/companies/{company}', [AdminController::class, 'updateCompany'])->name('admin.companies.update');
        Route::delete('/admin/companies/{company}', [AdminController::class, 'destroyCompany'])->name('admin.companies.destroy');
        
        // Subdivisions
        Route::get('/admin/subdivisions', [AdminController::class, 'subdivisions'])->name('admin.subdivisions');
        Route::get('/admin/subdivisions/create', [AdminController::class, 'createSubdivision'])->name('admin.subdivisions.create');
        Route::post('/admin/subdivisions', [AdminController::class, 'storeSubdivision'])->name('admin.subdivisions.store');
        Route::get('/admin/subdivisions/{subdivision}/edit', [AdminController::class, 'editSubdivision'])->name('admin.subdivisions.edit');
        Route::put('/admin/subdivisions/{subdivision}', [AdminController::class, 'updateSubdivision'])->name('admin.subdivisions.update');
        Route::delete('/admin/subdivisions/{subdivision}', [AdminController::class, 'destroySubdivision'])->name('admin.subdivisions.destroy');
        Route::get('/admin/subdivisions/{subdivision}/message', [AdminController::class, 'editSubdivisionMessage'])->name('admin.subdivisions.message');
        Route::put('/admin/subdivisions/{subdivision}/message', [AdminController::class, 'updateSubdivisionMessage'])->name('admin.subdivisions.update-message');
        
        // Users (SDO Management)
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        // Consumers
        Route::resource('admin/consumers', ConsumerController::class)->names([
            'index' => 'admin.consumers.index',
            'create' => 'admin.consumers.create',
            'store' => 'admin.consumers.store',
            'show' => 'admin.consumers.show',
            'edit' => 'admin.consumers.edit',
            'update' => 'admin.consumers.update',
            'destroy' => 'admin.consumers.destroy',
        ]);
        Route::get('/admin/consumers/{consumer}/history', [ConsumerController::class, 'history'])->name('admin.consumers.history');
        Route::get('/admin/consumers/{consumer}/applications', [ConsumerController::class, 'applications'])->name('admin.consumers.applications');
        
        // Meters
        Route::get('/admin/meters', [MeterController::class, 'index'])->name('admin.meters.index');
        Route::get('/admin/meters/create', [MeterController::class, 'create'])->name('admin.meters.create');
        Route::post('/admin/meters', [MeterController::class, 'store'])->name('admin.meters.store');
        Route::get('/admin/meters/{meter}/assign', [MeterController::class, 'assign'])->name('admin.meters.assign');
        Route::post('/admin/meters/{meter}/assign', [MeterController::class, 'storeAssignment'])->name('admin.meters.store-assignment');
        Route::post('/admin/meters/{meter}/assign/get-application', [MeterController::class, 'getApplicationDetails'])->name('admin.meters.get-application-details');
        Route::get('/admin/meters/{meter}', [MeterController::class, 'show'])->name('admin.meters.show');
        Route::get('/admin/meters/{meter}/edit', [MeterController::class, 'edit'])->name('admin.meters.edit');
        Route::put('/admin/meters/{meter}', [MeterController::class, 'update'])->name('admin.meters.update');
        Route::delete('/admin/meters/{meter}', [MeterController::class, 'destroy'])->name('admin.meters.destroy');
        Route::post('/admin/meters/{meter}/status', [MeterController::class, 'updateStatus'])->name('admin.meters.update-status');
        Route::get('/admin/meters/import/form', [MeterController::class, 'importForm'])->name('admin.meters.import-form');
        Route::get('/admin/meters/export', [MeterController::class, 'export'])->name('admin.meters.export');
        
        // Billing
        Route::get('/admin/billing', [BillingController::class, 'index'])->name('admin.billing.index');
        Route::get('/admin/billing/create', [BillingController::class, 'create'])->name('admin.billing.create');
        Route::post('/admin/billing', [BillingController::class, 'store'])->name('admin.billing.store');
        Route::get('/admin/billing/{bill}', [BillingController::class, 'show'])->name('admin.billing.show');
        Route::get('/admin/billing/{bill}/edit', [BillingController::class, 'edit'])->name('admin.billing.edit');
        Route::put('/admin/billing/{bill}', [BillingController::class, 'update'])->name('admin.billing.update');
        Route::post('/admin/billing/{bill}/verify', [BillingController::class, 'verify'])->name('admin.billing.verify');
        Route::get('/admin/billing/generate/form', [BillingController::class, 'generateForm'])->name('admin.billing.generate-form');
        Route::post('/admin/billing/generate', [BillingController::class, 'generateBills'])->name('admin.billing.generate');
        Route::get('/admin/billing/{bill}/pdf', [BillingController::class, 'downloadPdf'])->name('admin.billing.pdf');
        Route::get('/admin/billing/export', [BillingController::class, 'export'])->name('admin.billing.export');
        
        // Tariffs
        Route::resource('admin/tariffs', TariffController::class)->names([
            'index' => 'admin.tariffs.index',
            'create' => 'admin.tariffs.create',
            'store' => 'admin.tariffs.store',
            'show' => 'admin.tariffs.show',
            'edit' => 'admin.tariffs.edit',
            'update' => 'admin.tariffs.update',
            'destroy' => 'admin.tariffs.destroy',
        ]);
        Route::post('/admin/tariffs/{tariff}/toggle', [TariffController::class, 'toggleStatus'])->name('admin.tariffs.toggle');
        
        // Complaints
        Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
        Route::get('/admin/complaints/create', [ComplaintController::class, 'create'])->name('admin.complaints.create');
        Route::post('/admin/complaints', [ComplaintController::class, 'store'])->name('admin.complaints.store');
        Route::get('/admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
        Route::get('/admin/complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('admin.complaints.edit');
        Route::put('/admin/complaints/{complaint}', [ComplaintController::class, 'update'])->name('admin.complaints.update');
        Route::post('/admin/complaints/{complaint}/reassign', [ComplaintController::class, 'reassign'])->name('admin.complaints.reassign');
        Route::post('/admin/complaints/{complaint}/comment', [ComplaintController::class, 'addComment'])->name('admin.complaints.comment');
        Route::post('/admin/complaints/{complaint}/close', [ComplaintController::class, 'close'])->name('admin.complaints.close');
        Route::post('/admin/complaints/bulk-reassign', [ComplaintController::class, 'bulkReassign'])->name('admin.complaints.bulk-reassign');
        Route::get('/admin/complaints/export', [ComplaintController::class, 'export'])->name('admin.complaints.export');
        
        // Analytics & Reports
        Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics.index');
        Route::get('/admin/analytics/revenue', [AnalyticsController::class, 'revenueReport'])->name('admin.analytics.revenue');
        Route::get('/admin/analytics/complaints', [AnalyticsController::class, 'complaintReport'])->name('admin.analytics.complaints');
        Route::get('/admin/analytics/faulty-meters', [AnalyticsController::class, 'faultyMetersReport'])->name('admin.analytics.faulty-meters');
        Route::get('/admin/analytics/collection', [AnalyticsController::class, 'collectionReport'])->name('admin.analytics.collection');
        Route::get('/admin/analytics/high-loss', [AnalyticsController::class, 'highLossReport'])->name('admin.analytics.high-loss');
        Route::get('/admin/analytics/revenue-trend', [AnalyticsController::class, 'revenueTrend'])->name('admin.analytics.revenue-trend');
        Route::post('/admin/analytics/export', [AnalyticsController::class, 'exportReport'])->name('admin.analytics.export');
        Route::get('/admin/analytics/dashboard-stats', [AnalyticsController::class, 'dashboardStats'])->name('admin.analytics.dashboard-stats');
        
        // Global Search
        Route::get('/admin/search', [SearchController::class, 'search'])->name('admin.search');
        Route::get('/admin/search/quick', [SearchController::class, 'quickSearch'])->name('admin.search.quick');
        
        // Applications
        Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');
        Route::get('/admin/applications/{application}/edit', [AdminController::class, 'editApplication'])->name('admin.applications.edit');
        Route::put('/admin/applications/{application}', [AdminController::class, 'updateApplication'])->name('admin.applications.update');
        Route::get('/admin/applications/{application}/history', [AdminController::class, 'applicationHistory'])->name('admin.applications.history');
        
        // Data Export
        Route::get('/admin/export', [AdminController::class, 'exportData'])->name('admin.export');
        
        // LS Management
        Route::get('/admin/ls-management', [AdminController::class, 'lsManagement'])->name('admin.ls-management');
        Route::get('/admin/ls-management/{user}/permissions', [AdminController::class, 'lsPermissions'])->name('admin.ls-management.permissions');
        Route::put('/admin/ls-management/{user}/permissions', [AdminController::class, 'updateLsPermissions'])->name('admin.ls-management.update-permissions');
        Route::post('/admin/ls-management/{user}/suspend', [AdminController::class, 'suspendLsUser'])->name('admin.ls-management.suspend');
        Route::post('/admin/ls-management/{user}/activate', [AdminController::class, 'activateLsUser'])->name('admin.ls-management.activate');
        Route::post('/admin/ls-management/subdivision/{subdivision}/close', [AdminController::class, 'closeSubdivision'])->name('admin.ls-management.close-subdivision');
        Route::post('/admin/ls-management/subdivision/{subdivision}/open', [AdminController::class, 'openSubdivision'])->name('admin.ls-management.open-subdivision');
        
        // Audit Logs
        Route::get('/admin/activity-logs', [AdminController::class, 'activityLogs'])->name('admin.activity-logs');
        Route::get('/admin/audit-logs', function() {
            $perPage = request()->get('page', 1) == 1 ? 27 : 15;
            $logs = \App\Models\AuditLog::with('user')
                ->latest()
                ->paginate($perPage);
            return view('admin.audit-logs.index', compact('logs'));
        })->name('admin.audit-logs');
        
        // Global Summary Routes
        Route::resource('global-summaries', GlobalSummaryController::class)->names([
            'index' => 'admin.global-summaries.index',
            'create' => 'admin.global-summaries.create',
            'store' => 'admin.global-summaries.store',
            'show' => 'admin.global-summaries.show',
            'edit' => 'admin.global-summaries.edit',
            'update' => 'admin.global-summaries.update',
            'destroy' => 'admin.global-summaries.destroy',
        ])->parameters(['global-summaries' => 'globalSummary']);

    });
    
    // User Panel (auth only)
    Route::middleware('auth')->get('/user/panel', [UserPanelController::class, 'index'])->name('user.panel');

    // LS Routes
    Route::middleware('ls')->group(function () {
        // Dashboard
        Route::get('/ls/dashboard', [LsController::class, 'dashboard'])->name('ls.dashboard');
        
        // Applications
        Route::get('/ls/subdivisions/{subdivisionId}/applications', [LsController::class, 'applications'])->name('ls.applications');
        Route::get('/ls/applications/{applicationId}/edit', [LsController::class, 'editApplication'])->name('ls.edit-application');
        Route::put('/ls/applications/{applicationId}', [LsController::class, 'updateApplication'])->name('ls.update-application');
        Route::get('/ls/applications/{applicationId}/history', [LsController::class, 'applicationHistory'])->name('ls.application-history');
        Route::get('/ls/applications/{applicationId}/history/create', [LsController::class, 'createApplicationHistory'])->name('ls.create-application-history');
        Route::post('/ls/applications/{applicationId}/history', [LsController::class, 'storeApplicationHistory'])->name('ls.store-application-history');
        
        // Global Summary
        Route::get('/ls/applications/{applicationId}/global-summary/create', [LsController::class, 'createGlobalSummary'])->name('ls.create-global-summary');
        Route::post('/ls/applications/{applicationId}/global-summary', [LsController::class, 'storeGlobalSummary'])->name('ls.store-global-summary');
        
        // Extra Summaries
        Route::get('/ls/extra-summaries', [LsController::class, 'extraSummaries'])->name('ls.extra-summaries');
        Route::get('/ls/extra-summaries/create', [LsController::class, 'createExtraSummary'])->name('ls.create-extra-summary');
        Route::post('/ls/extra-summaries', [LsController::class, 'storeExtraSummary'])->name('ls.store-extra-summary');
        Route::get('/ls/extra-summaries/{id}/edit', [LsController::class, 'editExtraSummary'])->name('ls.edit-extra-summary');
        Route::put('/ls/extra-summaries/{id}', [LsController::class, 'updateExtraSummary'])->name('ls.update-extra-summary');
        Route::delete('/ls/extra-summaries/{id}', [LsController::class, 'destroyExtraSummary'])->name('ls.destroy-extra-summary');
        
        // Meter Store
        Route::get('/ls/meter-store', [LsController::class, 'meterStore'])->name('ls.meter-store');
        
        // Switch Subdivision
        Route::post('/ls/switch-subdivision', [LsController::class, 'switchSubdivision'])->name('ls.switch-subdivision');
    });
});

// LS Login Routes (outside auth middleware)
Route::get('/ls/select-subdivision', [LsController::class, 'selectSubdivision'])->name('ls.select-subdivision');
Route::get('/ls/login/{subdivision}', [LsController::class, 'showLogin'])->name('ls.login');
Route::post('/ls/authenticate', [LsController::class, 'authenticate'])->name('ls.authenticate');

// SDC Routes
Route::middleware('auth')->group(function () {
    Route::middleware('sdc')->group(function () {
        Route::get('/sdc/dashboard', [SDCController::class, 'dashboard'])->name('sdc.dashboard');
        Route::get('/sdc/global-summaries', [SDCController::class, 'globalSummaries'])->name('sdc.global-summaries');
        Route::get('/sdc/global-summaries/{applicationId}/create', [SDCController::class, 'createGlobalSummary'])->name('sdc.create-global-summary');
        Route::post('/sdc/global-summaries/{applicationId}', [SDCController::class, 'storeGlobalSummary'])->name('sdc.store-global-summary');
        Route::get('/sdc/global-summaries/{id}/edit', [SDCController::class, 'editGlobalSummary'])->name('sdc.edit-global-summary');
        Route::put('/sdc/global-summaries/{id}', [SDCController::class, 'updateGlobalSummary'])->name('sdc.update-global-summary');
        Route::post('/sdc/switch-subdivision', [SDCController::class, 'switchSubdivision'])->name('sdc.switch-subdivision');
    });
});

// SDC Login Routes (outside auth middleware)
Route::get('/sdc/select-subdivision', [SDCController::class, 'selectSubdivision'])->name('sdc.select-subdivision');
Route::get('/sdc/login/{subdivision}', [SDCController::class, 'showLogin'])->name('sdc.login');
Route::post('/sdc/authenticate', [SDCController::class, 'authenticate'])->name('sdc.authenticate');

// RO Routes
Route::middleware('auth')->group(function () {
    Route::middleware('ro')->group(function () {
        Route::get('/ro/dashboard', [ROController::class, 'dashboard'])->name('ro.dashboard');
        Route::get('/ro/summary/{id}', [ROController::class, 'viewSummary'])->name('ro.view-summary');
        Route::get('/ro/billing/{summaryId}/manage', [ROController::class, 'manageBilling'])->name('ro.manage-billing');
        Route::post('/ro/billing/{summaryId}/create', [ROController::class, 'createBill'])->name('ro.create-bill');
        Route::post('/ro/switch-subdivision', [ROController::class, 'switchSubdivision'])->name('ro.switch-subdivision');
    });
});

// RO Login Routes (outside auth middleware)
Route::get('/ro/select-subdivision', [ROController::class, 'selectSubdivision'])->name('ro.select-subdivision');
Route::get('/ro/login/{subdivision}', [ROController::class, 'showLogin'])->name('ro.login');
Route::post('/ro/authenticate', [ROController::class, 'authenticate'])->name('ro.authenticate');

// API Routes for AJAX
Route::get('/api/check-meter/{meterNumber}', function($meterNumber) {
    $meter = \App\Models\Meter::where('meter_no', $meterNumber)->with('consumer')->first();
    
    if ($meter) {
        return response()->json([
            'exists' => true,
            'consumer' => $meter->consumer->name ?? 'Unknown',
            'meter' => $meter
        ]);
    }
    
    return response()->json(['exists' => false]);
});

Route::get('/api/subdivisions/{companyId}', function($companyId) {
    $subdivisions = \App\Models\Subdivision::where('company_id', $companyId)
        ->orderBy('name')
        ->get(['id', 'name', 'subdivision_message']);
    return response()->json($subdivisions);
});

require __DIR__.'/auth.php';