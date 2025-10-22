@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Admin Panel</h2>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="text-blue-600 text-3xl font-bold">{{ $totalUsers ?? 0 }}</div>
                        <div class="text-gray-600">Total Users</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="text-green-600 text-3xl font-bold">{{ $totalCompanies ?? 0 }}</div>
                        <div class="text-gray-600">Companies</div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <div class="text-yellow-600 text-3xl font-bold">{{ $totalSubdivisions ?? 0 }}</div>
                        <div class="text-gray-600">Subdivisions</div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-6">
                        <div class="text-purple-600 text-3xl font-bold">{{ $totalApplications ?? 0 }}</div>
                        <div class="text-gray-600">Applications</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Applications -->
                    <div class="bg-white rounded-lg border p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Applications</h3>
                        
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentApplications as $application)
                                    <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between">
                                            <div>
                                                <div class="font-medium">{{ $application->application_no }}</div>
                                                <div class="text-sm text-gray-500">{{ $application->customer_name }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($application->status == 'approved') bg-green-100 text-green-800 
                                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800 
                                                        @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $application->created_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $application->company->name ?? 'N/A' }} - {{ $application->subdivision->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No recent applications found.</p>
                        @endif
                    </div>
                    
                    <!-- Recent Users -->
                    <div class="bg-white rounded-lg border p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Users</h3>
                        
                        @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentUsers as $user)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-medium">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                        <div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($user->role == 'admin') bg-blue-100 text-blue-800 
                                                @elseif($user->role == 'ls') bg-green-100 text-green-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No recent users found.</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Management Sections</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Store Page for Meter (Only Admin) -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Store Page</h3>
                            <p class="text-gray-600 mb-4">Manage meter storage activities</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Access Store Page →</a>
                        </div>
                        
                        <!-- Company Management (Only Admin) -->
                        <div class="bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Company Management</h3>
                            <p class="text-gray-600 mb-4">Manage company information</p>
                            <a href="{{ route('admin.companies') }}" class="text-green-600 hover:text-green-800 font-medium">Manage Companies →</a>
                        </div>
                        
                        <!-- Subdivision Management (Only Admin) -->
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Subdivision Management</h3>
                            <p class="text-gray-600 mb-4">Manage subdivisions and credentials</p>
                            <a href="{{ route('admin.subdivisions') }}" class="text-yellow-600 hover:text-yellow-800 font-medium">Manage Subdivisions →</a>
                        </div>
                        
                        <!-- Status Update of Applications -->
                        <div class="bg-purple-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Application Status</h3>
                            <p class="text-gray-600 mb-4">Update application status and add fees</p>
                            <a href="{{ route('admin.applications') }}" class="text-purple-600 hover:text-purple-800 font-medium">Update Status →</a>
                        </div>
                        
                        <!-- Global Summary Management -->
                        <div class="bg-indigo-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Global Summaries</h3>
                            <p class="text-gray-600 mb-4">Manage global summary records</p>
                            <a href="{{ route('admin.global-summaries.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Manage Summaries →</a>
                        </div>
                        
                        <!-- Activity Logs -->
                        <div class="bg-pink-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Activity Logs</h3>
                            <p class="text-gray-600 mb-4">View application logs and activities</p>
                            <a href="{{ route('admin.activity-logs') }}" class="text-pink-600 hover:text-pink-800 font-medium">View Logs →</a>
                        </div>
                        
                        <!-- User Management -->
                        <div class="bg-teal-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">User Management</h3>
                            <p class="text-gray-600 mb-4">Manage users and roles</p>
                            <a href="{{ route('admin.users') }}" class="text-teal-600 hover:text-teal-800 font-medium">Manage Users →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection