@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Track Your Application</h1>
        <p class="text-xl text-gray-600">Check the status of your electricity connection application</p>
    </div>
    
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
        <div class="mb-8">
            <form method="GET" action="{{ route('track') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <label for="application_no" class="block text-sm font-medium text-gray-700 mb-2">Application Number</label>
                    <input type="text" id="application_no" name="application_no" placeholder="Enter your application number" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                           value="{{ request('application_no') }}" autocomplete="off">
                </div>
                <div class="self-end">
                    <button type="submit" class="h-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                        Track Application
                    </button>
                </div>
            </form>
        </div>
        
        @if(request('application_no'))
            @php
                $application = \App\Models\Application::with(['company', 'subdivision', 'histories'])->where('application_no', request('application_no'))->first();
            @endphp
            
            @if($application)
                <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Application Details</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($application->status == 'approved') bg-green-100 text-green-800 
                            @elseif($application->status == 'rejected') bg-red-100 text-red-800 
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Application Number</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->application_no }}</div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Customer Name</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->customer_name }}</div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Company</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->company->name ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Subdivision</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->subdivision->name ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Meter Number</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->meter_no ?? 'N/A' }}</div>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Application Date</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $application->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    
                    @if($application->histories->count() > 0)
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 mb-4">Application History</h4>
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($application->histories as $history)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $history->created_at->format('M d, Y H:i') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $history->action }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ $history->remarks ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-red-800">Application Not Found</h3>
                            <div class="mt-2 text-red-700">
                                <p>No application found with the provided application number. Please check the number and try again.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-8 text-center">
                <div class="max-w-md mx-auto">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Track Your Application</h3>
                    <p class="text-gray-700 mb-5">Enter your application number in the field above to check the status of your electricity connection application.</p>
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-gray-600">Your application number can be found in the confirmation email or SMS sent after submitting your application.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection