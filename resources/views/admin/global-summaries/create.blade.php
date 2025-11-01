@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Create Global Summary</h2>

                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600 dark:text-red-400">There were some errors:</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.global-summaries.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Application -->
                        <div class="md:col-span-2">
                            <label for="application_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application</label>
                            <select name="application_id" id="application_id" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Select an Application</option>
                                @foreach($applications as $application)
                                    <option value="{{ $application->id }}" {{ old('application_id') == $application->id ? 'selected' : '' }}>
                                        {{ $application->application_no }} - {{ $application->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SIM Number -->
                        <div>
                            <label for="sim_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SIM Number</label>
                            <input type="text" name="sim_number" id="sim_number" value="{{ old('sim_number') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Enter SIM number">
                        </div>

                        <!-- Consumer Address -->
                        <div class="md:col-span-2">
                            <label for="consumer_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Consumer Address</label>
                            <textarea name="consumer_address" id="consumer_address" rows="3"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('consumer_address') }}</textarea>
                        </div>

                        <!-- Date on Draft on Store -->
                        <div>
                            <label for="date_on_draft_store" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date on Draft on Store</label>
                            <input type="date" name="date_on_draft_store" id="date_on_draft_store" value="{{ old('date_on_draft_store') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <!-- Date of Received by LM/Consumer -->
                        <div>
                            <label for="date_received_lm_consumer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Received by LM/Consumer</label>
                            <input type="date" name="date_received_lm_consumer" id="date_received_lm_consumer" value="{{ old('date_received_lm_consumer') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <!-- Customer Mobile No -->
                        <div>
                            <label for="customer_mobile_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Mobile No</label>
                            <input type="text" name="customer_mobile_no" id="customer_mobile_no" value="{{ old('customer_mobile_no') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="e.g., 03001234567">
                        </div>

                        <!-- Customer SC No -->
                        <div>
                            <label for="customer_sc_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer SC No</label>
                            <input type="text" name="customer_sc_no" id="customer_sc_no" value="{{ old('customer_sc_no') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <!-- Date of Return to SDC for Billing -->
                        <div>
                            <label for="date_return_sdc_billing" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Return to SDC for Billing</label>
                            <input type="date" name="date_return_sdc_billing" id="date_return_sdc_billing" value="{{ old('date_return_sdc_billing') }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.global-summaries.index') }}" class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">Cancel</a>
                        <button type="submit" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Create Global Summary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection