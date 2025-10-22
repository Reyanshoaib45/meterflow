@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">LS Dashboard</h2>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-6">
                        <div class="text-blue-600 text-3xl font-bold">{{ $totalApplications ?? 0 }}</div>
                        <div class="text-gray-600">Total Applications</div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-6">
                        <div class="text-yellow-600 text-3xl font-bold">{{ $pendingApplications ?? 0 }}</div>
                        <div class="text-gray-600">Pending Applications</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-6">
                        <div class="text-green-600 text-3xl font-bold">{{ $approvedApplications ?? 0 }}</div>
                        <div class="text-gray-600">Approved Applications</div>
                    </div>
                </div>

                <!-- Subdivisions Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Your Subdivisions</h3>
                    @if(isset($subdivisions) && $subdivisions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($subdivisions as $subdivision)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <h4 class="font-medium text-lg">{{ $subdivision->name }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $subdivision->company->name ?? 'No Company' }}</p>
                                    <div class="mt-3">
                                        <a href="{{ route('ls.applications', $subdivision->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Applications →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">You don't have any subdivisions assigned yet.</p>
                    @endif
                </div>

                <!-- Recent Applications -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Recent Applications</h3>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                    
                    @if(isset($applications) && $applications->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($applications as $application)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->application_no }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $application->customer_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($application->status == 'approved') bg-green-100 text-green-800 
                                                    @elseif($application->status == 'rejected') bg-red-100 text-red-800 
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('ls.edit-application', $application->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                <a href="{{ route('ls.create-global-summary', $application->id) }}" class="text-blue-600 hover:text-blue-900">Global Summary</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No applications found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection