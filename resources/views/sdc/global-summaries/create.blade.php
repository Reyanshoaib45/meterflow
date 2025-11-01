@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Create Global Summary</h2>
                    <a href="{{ route('sdc.global-summaries') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                        ← Back
                    </a>
                </div>

                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Application No</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $application->application_no }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Customer Name</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $application->customer_name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter No</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $application->meter_number ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <div class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($application->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('sdc.store-global-summary', $application->id) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="sim_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SIM Number</label>
                            <input type="text" name="sim_number" id="sim_number" value="{{ old('sim_number') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Enter SIM number">
                        </div>

                        <div class="md:col-span-2">
                            <label for="consumer_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Consumer Address</label>
                            <textarea name="consumer_address" id="consumer_address" rows="3"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('consumer_address', $application->address) }}</textarea>
                        </div>

                        <div>
                            <label for="date_on_draft_store" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date on Draft on Store</label>
                            <input type="date" name="date_on_draft_store" id="date_on_draft_store" value="{{ old('date_on_draft_store') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="date_received_lm_consumer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Received by LM/Consumer</label>
                            <input type="date" name="date_received_lm_consumer" id="date_received_lm_consumer" value="{{ old('date_received_lm_consumer') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="customer_mobile_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Mobile No</label>
                            <input type="text" name="customer_mobile_no" id="customer_mobile_no" value="{{ old('customer_mobile_no') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                   placeholder="e.g., 03001234567">
                        </div>

                        <div>
                            <label for="customer_sc_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer SC No</label>
                            <input type="text" name="customer_sc_no" id="customer_sc_no" value="{{ old('customer_sc_no') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="date_return_sdc_billing" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Return to SDC for Billing</label>
                            <input type="date" name="date_return_sdc_billing" id="date_return_sdc_billing" value="{{ old('date_return_sdc_billing') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('sdc.global-summaries') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                            Cancel
                        </a>
                        <button type="submit" class="bg-purple-600 dark:bg-purple-700 text-white px-4 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                            Create Global Summary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

