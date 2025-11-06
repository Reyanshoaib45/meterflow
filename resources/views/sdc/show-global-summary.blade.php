@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Global Summary Details</h2>
                <a href="{{ route('sdc.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Back to Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Application Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Application No</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->application_no ?? $application->application_no }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer Name</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->customer_name ?? $application->customer_name }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Meter No</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->meter_no ?? $application->meter_number ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer Mobile No</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->customer_mobile_no ?? $application->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Details -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary Details</h3>
                    
                    <div class="space-y-4">
                        @if($application->meter)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">SIM Number</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->meter->sim_number ?? 'N/A' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">SEO Number</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->meter->seo_number ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Installation Date</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                @if($application->meter->installed_on)
                                    {{ $application->meter->installed_on->format('Y-m-d') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 dark:text-gray-400">Meter details not yet added.</p>
                            <a href="{{ route('sdc.edit-meter', $application->id) }}" class="mt-2 inline-block text-purple-600 hover:text-purple-900 dark:text-purple-400">
                                Add Meter Details
                            </a>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date on Draft on Store</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->date_on_draft_store ? $globalSummary->date_on_draft_store->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Received by LM/Consumer</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->date_received_lm_consumer ? $globalSummary->date_received_lm_consumer->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer SC No</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->customer_sc_no ?? 'N/A' }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date of Return to SDC for Billing</label>
                            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $globalSummary->date_return_sdc_billing ? $globalSummary->date_return_sdc_billing->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Application Details -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Related Application Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Application Status</label>
                        <div class="mt-1">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Company</label>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->company->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subdivision</label>
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->subdivision->name ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer Address</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $application->address ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Connection Type</label>
                        <div class="text-sm text-gray-900 dark:text-white">{{ $application->connection_type ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fee Amount</label>
                        <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                            {{ $application->fee_amount ? 'Rs. ' . number_format($application->fee_amount, 2) : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3">
                @php
                    $assignmentHistory = \App\Models\ApplicationHistory::where('application_id', $application->id)
                        ->where('action_type', 'status_changed')
                        ->where('remarks', 'like', '%assigned_sdc_id%')
                        ->latest()
                        ->first();
                    $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $application->updated_at;
                    $hoursSinceAssignment = now()->diffInHours($assignmentTime);
                    $canEdit = $hoursSinceAssignment <= 24;
                @endphp
                
                @if($canEdit && !$application->meter)
                <a href="{{ route('sdc.edit-meter', $application->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Add Meter Details
                </a>
                @elseif($canEdit && $application->meter)
                <a href="{{ route('sdc.edit-meter', $application->id) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Edit Meter Details
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

