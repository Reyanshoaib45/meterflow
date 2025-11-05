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