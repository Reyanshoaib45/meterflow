@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Global Summary Details</h2>
                    <div>
                        <a href="{{ route('admin.global-summaries.edit', $globalSummary) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                            Edit
                        </a>
                        <a href="{{ route('admin.global-summaries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Application Information</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Application No</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->application_no }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Customer Name</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->customer_name }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Meter No</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->meter_no ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Summary Details</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">SIM Number</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->sim_number ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Consumer Address</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->consumer_address ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date on Draft on Store</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->date_on_draft_store ? $globalSummary->date_on_draft_store->format('Y-m-d') : 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date of Received by LM/Consumer</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->date_received_lm_consumer ? $globalSummary->date_received_lm_consumer->format('Y-m-d') : 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Customer Mobile No</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->customer_mobile_no ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Customer SC No</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->customer_sc_no ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date of Return to SDC for Billing</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->date_return_sdc_billing ? $globalSummary->date_return_sdc_billing->format('Y-m-d') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attachments (NOC and Demand Notice) - Only visible to RO and Admin -->
                @if($globalSummary->noc_file || $globalSummary->demand_notice_file)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments</h3>
                        <div class="border rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($globalSummary->noc_file)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">NOC File</label>
                                        <a href="{{ asset('storage/' . $globalSummary->noc_file) }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            View NOC File
                                        </a>
                                    </div>
                                @endif

                                @if($globalSummary->demand_notice_file)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Demand Notice File</label>
                                        <a href="{{ asset('storage/' . $globalSummary->demand_notice_file) }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            View Demand Notice
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Related Application</h3>
                    
                    @if($globalSummary->application)
                    <div class="border rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Application Status</label>
                                <div class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($globalSummary->application->status == 'approved') bg-green-100 text-green-800 
                                        @elseif($globalSummary->application->status == 'rejected') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($globalSummary->application->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Company</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->application->company->name ?? 'N/A' }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Subdivision</label>
                                <div class="mt-1 text-sm text-gray-900">{{ $globalSummary->application->subdivision->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500">No related application found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection