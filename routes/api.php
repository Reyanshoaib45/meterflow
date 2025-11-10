<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationApiController;
use App\Http\Controllers\Api\MeterApiController;
use App\Http\Controllers\Api\BillingApiController;
use App\Http\Controllers\Api\ComplaintApiController;
use App\Http\Controllers\Api\ConsumerApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\SubdivisionApiController;
use App\Http\Controllers\Api\TariffApiController;
use App\Http\Controllers\Api\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Public application tracking
    Route::post('/applications/track', [ApplicationApiController::class, 'track']);
    
    // Public complaint tracking
    Route::post('/complaints/track', [ComplaintApiController::class, 'track']);
    
    // Public data
    Route::get('/companies', [ApplicationApiController::class, 'companies']);
    Route::get('/subdivisions', [ApplicationApiController::class, 'subdivisions']);
    
    // Public complaint submission
    Route::post('/complaints', [ComplaintApiController::class, 'store']);
    
    // Public application submission
    Route::post('/applications', [ApplicationApiController::class, 'store']);
    
    // Check meter number
    Route::post('/meters/check', [MeterApiController::class, 'checkMeterNumber']);
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Authentication
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Dashboard & Statistics
    Route::get('/dashboard/admin', [DashboardApiController::class, 'adminStats']);
    Route::get('/dashboard/ls', [DashboardApiController::class, 'lsStats']);
    Route::get('/dashboard/activities', [DashboardApiController::class, 'recentActivities']);
    Route::get('/dashboard/revenue-trend', [DashboardApiController::class, 'revenueTrend']);
    Route::get('/dashboard/subdivisions', [DashboardApiController::class, 'subdivisionStats']);
    
    // Applications
    Route::get('/applications', [ApplicationApiController::class, 'index']);
    Route::get('/applications/{id}', [ApplicationApiController::class, 'show']);
    Route::put('/applications/{id}', [ApplicationApiController::class, 'update']);
    Route::delete('/applications/{id}', [ApplicationApiController::class, 'destroy']);
    Route::post('/applications/{id}/status', [ApplicationApiController::class, 'updateStatus']);
    
    // Meters
    Route::get('/meters', [MeterApiController::class, 'index']);
    Route::post('/meters', [MeterApiController::class, 'store']);
    Route::get('/meters/{id}', [MeterApiController::class, 'show']);
    Route::put('/meters/{id}', [MeterApiController::class, 'update']);
    Route::delete('/meters/{id}', [MeterApiController::class, 'destroy']);
    
    // Billing
    Route::get('/bills', [BillingApiController::class, 'index']);
    Route::get('/bills/{id}', [BillingApiController::class, 'show']);
    Route::post('/bills/search', [BillingApiController::class, 'getByBillNumber']);
    Route::get('/billing/statistics', [BillingApiController::class, 'statistics']);
    
    // Complaints
    Route::get('/complaints', [ComplaintApiController::class, 'index']);
    Route::get('/complaints/{id}', [ComplaintApiController::class, 'show']);
    Route::put('/complaints/{id}', [ComplaintApiController::class, 'update']);
    Route::delete('/complaints/{id}', [ComplaintApiController::class, 'destroy']);
    
    // Consumers
    Route::get('/consumers', [ConsumerApiController::class, 'index']);
    Route::get('/consumers/{id}', [ConsumerApiController::class, 'show']);
    Route::post('/consumers/find-by-cnic', [ConsumerApiController::class, 'findByCnic']);
    Route::get('/consumers/{id}/history', [ConsumerApiController::class, 'history']);
    Route::post('/consumers', [ConsumerApiController::class, 'store']);
    Route::put('/consumers/{id}', [ConsumerApiController::class, 'update']);
    Route::delete('/consumers/{id}', [ConsumerApiController::class, 'destroy']);
    
    // Admin APIs (admin role required)
    Route::middleware('admin')->group(function () {
        // Companies
        Route::get('/companies/admin', [CompanyApiController::class, 'index']);
        Route::get('/companies/admin/{id}', [CompanyApiController::class, 'show']);
        Route::post('/companies/admin', [CompanyApiController::class, 'store']);
        Route::put('/companies/admin/{id}', [CompanyApiController::class, 'update']);
        Route::delete('/companies/admin/{id}', [CompanyApiController::class, 'destroy']);
        
        // Subdivisions
        Route::get('/subdivisions/admin', [SubdivisionApiController::class, 'index']);
        Route::get('/subdivisions/admin/{id}', [SubdivisionApiController::class, 'show']);
        Route::post('/subdivisions/admin', [SubdivisionApiController::class, 'store']);
        Route::put('/subdivisions/admin/{id}', [SubdivisionApiController::class, 'update']);
        Route::delete('/subdivisions/admin/{id}', [SubdivisionApiController::class, 'destroy']);
        
        // Tariffs
        Route::get('/tariffs', [TariffApiController::class, 'index']);
        Route::get('/tariffs/{id}', [TariffApiController::class, 'show']);
        Route::post('/tariffs', [TariffApiController::class, 'store']);
        Route::put('/tariffs/{id}', [TariffApiController::class, 'update']);
        Route::delete('/tariffs/{id}', [TariffApiController::class, 'destroy']);
        
        // Users
        Route::get('/users', [UserApiController::class, 'index']);
        Route::get('/users/{id}', [UserApiController::class, 'show']);
        Route::post('/users', [UserApiController::class, 'store']);
        Route::put('/users/{id}', [UserApiController::class, 'update']);
        Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
    });
});
