@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold">Application History</h2>
                        <p class="text-gray-600 mt-1">Application No: <span class="font-semibold">{{ $application->application_no }}</span></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.applications.edit', $application) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Application
                        </a>
                        <a href="{{ route('admin.applications') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Applications
                        </a>
                    </div>
                </div>

                <!-- Application Details Card -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Customer Name</label>
                            <p class="font-semibold">{{ $application->customer_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">CNIC</label>
                            <p class="font-semibold">{{ $application->cnic }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Current Status</label>
                            <p>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($application->status == 'approved') bg-green-100 text-green-800 
                                    @elseif($application->status == 'rejected') bg-red-100 text-red-800 
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Phone</label>
                            <p class="font-semibold">{{ $application->phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Subdivision</label>
                            <p class="font-semibold">{{ $application->subdivision->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Connection Type</label>
                            <p class="font-semibold">{{ $application->connection_type }}</p>
                        </div>
                    </div>
                </div>

                <!-- History Timeline -->
                <h3 class="text-lg font-semibold mb-4">Status History</h3>
                
                @if($history && $history->count() > 0)
                    <div class="space-y-4">
                        @foreach($history as $record)
                            <div class="flex gap-4 border-l-4 
                                @if($record->action_type == 'approved' || $record->action_type == 'status_changed') border-blue-500
                                @elseif($record->action_type == 'rejected') border-red-500
                                @else border-gray-300 @endif
                                pl-4 py-3 bg-gray-50 rounded">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        @if($record->action_type == 'approved' || $record->action_type == 'status_changed') bg-blue-100
                                        @elseif($record->action_type == 'rejected') bg-red-100
                                        @else bg-gray-200 @endif">
                                        <svg class="w-5 h-5 
                                            @if($record->action_type == 'approved' || $record->action_type == 'status_changed') text-blue-600
                                            @elseif($record->action_type == 'rejected') text-red-600
                                            @else text-gray-600 @endif" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ ucfirst(str_replace('_', ' ', $record->action_type)) }}
                                            </p>
                                            <p class="text-gray-600 mt-1">{{ $record->remarks }}</p>
                                            @if($record->user)
                                                <p class="text-sm text-gray-500 mt-2">
                                                    By: <span class="font-medium">{{ $record->user->name }}</span>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm text-gray-500">
                                            <p>{{ $record->created_at->format('d M, Y') }}</p>
                                            <p>{{ $record->created_at->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2">No history records found for this application.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
