@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Search -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            
            <!-- Global Search -->
            <div class="w-96">
                <form action="{{ route('admin.search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Search consumers, meters, bills..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Subdivisions -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Subdivisions</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['subdivisions'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Consumers -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Consumers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['consumers'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Meters -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Meters</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['meters']['active'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Faulty: {{ $stats['meters']['faulty'] }} | Disconnected: {{ $stats['meters']['disconnected'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue This Month -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Revenue (This Month)</p>
                        <p class="text-3xl font-bold text-gray-900">₨{{ number_format($stats['revenue']['this_month'], 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Pending: ₨{{ number_format($stats['revenue']['pending'], 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">Pending Complaints</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['complaints']['pending'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">In Progress</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['complaints']['in_progress'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">Resolved</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['complaints']['resolved'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <p class="text-sm text-gray-600">SDO Users</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['sdo_users'] }}</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Applications -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Applications</h3>
                </div>
                <div class="p-6">
                    @if($recentApplications && $recentApplications->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentApplications as $app)
                                <div class="flex items-center justify-between pb-3 border-b last:border-0">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $app->application_no }}</p>
                                        <p class="text-sm text-gray-600">{{ $app->customer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $app->subdivision->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($app->status == 'approved') bg-green-100 text-green-800 
                                            @elseif($app->status == 'rejected') bg-red-100 text-red-800 
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                        <a href="{{ route('admin.applications.edit', $app->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent applications</p>
                    @endif
                </div>
            </div>

            <!-- Recent Complaints -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Complaints</h3>
                </div>
                <div class="p-6">
                    @if($recentComplaints && $recentComplaints->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentComplaints as $complaint)
                                <div class="flex items-center justify-between pb-3 border-b last:border-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $complaint->complaint_id }}</p>
                                        <p class="text-sm text-gray-600">{{ $complaint->subject }}</p>
                                        <p class="text-xs text-gray-500">{{ $complaint->consumer->name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($complaint->status == 'resolved') bg-green-100 text-green-800 
                                        @elseif($complaint->status == 'pending') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent complaints</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Summary & Export -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Quick Summary & Data Export</h3>
                <div class="flex gap-2">
                    <button onclick="document.getElementById('export-modal').classList.remove('hidden')" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Data
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-600 font-medium">Total Applications</p>
                        <p class="text-2xl font-bold text-blue-900">{{ \App\Models\Application::count() }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-green-600 font-medium">Total Consumers</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['consumers'] }}</p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm text-yellow-600 font-medium">Total Meters</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['meters']['total'] }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm text-purple-600 font-medium">Total Bills</p>
                        <p class="text-2xl font-bold text-purple-900">{{ \App\Models\Bill::count() }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">Pending Complaints</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['complaints']['pending'] }}</p>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <p class="text-sm text-indigo-600 font-medium">Total Subdivisions</p>
                        <p class="text-2xl font-bold text-indigo-900">{{ $stats['subdivisions'] }}</p>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg">
                        <p class="text-sm text-pink-600 font-medium">LS Users</p>
                        <p class="text-2xl font-bold text-pink-900">{{ $stats['sdo_users'] }}</p>
                    </div>
                    <div class="bg-teal-50 p-4 rounded-lg">
                        <p class="text-sm text-teal-600 font-medium">Companies</p>
                        <p class="text-2xl font-bold text-teal-900">{{ \App\Models\Company::count() }}</p>
                    </div>
                    <div class="bg-emerald-50 p-4 rounded-lg">
                        <p class="text-sm text-emerald-600 font-medium">Global Summaries</p>
                        <p class="text-2xl font-bold text-emerald-900">{{ \App\Models\GlobalSummary::count() }}</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <p class="text-sm text-orange-600 font-medium">Extra Summaries</p>
                        <p class="text-2xl font-bold text-orange-900">{{ \App\Models\ExtraSummary::count() }}</p>
                    </div>
                    <div class="bg-cyan-50 p-4 rounded-lg">
                        <p class="text-sm text-cyan-600 font-medium">Audit Logs</p>
                        <p class="text-2xl font-bold text-cyan-900">{{ \App\Models\AuditLog::count() }}</p>
                    </div>
                    <div class="bg-lime-50 p-4 rounded-lg">
                        <p class="text-sm text-lime-600 font-medium">Users</p>
                        <p class="text-2xl font-bold text-lime-900">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Modal -->
        <div id="export-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Export Data</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.export', ['type' => 'consumers']) }}" 
                           class="block w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded border border-blue-200">
                            <span class="font-medium">Export Consumers</span>
                            <p class="text-sm text-gray-600">All consumer data with details</p>
                        </a>
                        <a href="{{ route('admin.export', ['type' => 'applications']) }}" 
                           class="block w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded border border-green-200">
                            <span class="font-medium">Export Applications</span>
                            <p class="text-sm text-gray-600">All application records</p>
                        </a>
                        <a href="{{ route('admin.export', ['type' => 'bills']) }}" 
                           class="block w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded border border-purple-200">
                            <span class="font-medium">Export Bills</span>
                            <p class="text-sm text-gray-600">Complete billing history</p>
                        </a>
                        <a href="{{ route('admin.export', ['type' => 'meters']) }}" 
                           class="block w-full text-left px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded border border-yellow-200">
                            <span class="font-medium">Export Meters</span>
                            <p class="text-sm text-gray-600">All meter information</p>
                        </a>
                        <a href="{{ route('admin.export', ['type' => 'complaints']) }}" 
                           class="block w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded border border-red-200">
                            <span class="font-medium">Export Complaints</span>
                            <p class="text-sm text-gray-600">All complaint records</p>
                        </a>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button onclick="document.getElementById('export-modal').classList.add('hidden')" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.subdivisions') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Subdivisions</span>
                    </a>

                    <a href="{{ route('admin.users') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">SDO Users</span>
                    </a>

                    <a href="{{ route('admin.meters.index') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Meters</span>
                    </a>

                    <a href="{{ route('admin.billing.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Billing</span>
                    </a>

                    <a href="{{ route('admin.tariffs.index') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Tariffs</span>
                    </a>

                    <a href="{{ route('admin.complaints.index') }}" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Complaints</span>
                    </a>

                    <a href="{{ route('admin.analytics.index') }}" class="flex flex-col items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
                        <svg class="w-8 h-8 text-pink-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Analytics</span>
                    </a>

                    <a href="{{ route('admin.consumers.index') }}" class="flex flex-col items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition">
                        <svg class="w-8 h-8 text-teal-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Consumers</span>
                    </a>

                    <a href="{{ route('admin.audit-logs') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Audit Logs</span>
                    </a>

                    <a href="{{ route('admin.applications') }}" class="flex flex-col items-center p-4 bg-cyan-50 rounded-lg hover:bg-cyan-100 transition">
                        <svg class="w-8 h-8 text-cyan-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Edit Applications</span>
                    </a>

                    <a href="{{ route('admin.global-summaries.index') }}" class="flex flex-col items-center p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                        <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Global Summaries</span>
                    </a>

                    <a href="{{ route('admin.ls-management') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                        <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">LS Management</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
