@extends('layouts.app')

@section('content')
<div class="py-12 page-transition">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in-up">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Edit Extra Summary</h2>
                    <p class="text-gray-600 mt-1">Update application summary details</p>
                </div>

                <form action="{{ route('ls.update-extra-summary', $extraSummary->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Application No -->
                        <div>
                            <label for="application_no" class="block text-sm font-medium text-gray-700">
                                Application No <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="application_no" id="application_no" 
                                value="{{ old('application_no', $extraSummary->application_no) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                required>
                            @error('application_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">
                                Customer Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="customer_name" id="customer_name" 
                                value="{{ old('customer_name', $extraSummary->customer_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                required>
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meter No -->
                        <div>
                            <label for="meter_no" class="block text-sm font-medium text-gray-700">
                                Meter No
                            </label>
                            <input type="text" name="meter_no" id="meter_no" 
                                value="{{ old('meter_no', $extraSummary->meter_no) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('meter_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mobile No -->
                        <div>
                            <label for="customer_mobile_no" class="block text-sm font-medium text-gray-700">
                                Customer Mobile No
                            </label>
                            <input type="text" name="customer_mobile_no" id="customer_mobile_no" 
                                value="{{ old('customer_mobile_no', $extraSummary->customer_mobile_no) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('customer_mobile_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SC No -->
                        <div>
                            <label for="customer_sc_no" class="block text-sm font-medium text-gray-700">
                                Customer SC No
                            </label>
                            <input type="text" name="customer_sc_no" id="customer_sc_no" 
                                value="{{ old('customer_sc_no', $extraSummary->customer_sc_no) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('customer_sc_no')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SIM Date -->
                        <div>
                            <label for="sim_date" class="block text-sm font-medium text-gray-700">
                                SIM Date
                            </label>
                            <input type="date" name="sim_date" id="sim_date" 
                                value="{{ old('sim_date', $extraSummary->sim_date ? $extraSummary->sim_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('sim_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date on Draft Store -->
                        <div>
                            <label for="date_on_draft_store" class="block text-sm font-medium text-gray-700">
                                Date on Draft Store
                            </label>
                            <input type="date" name="date_on_draft_store" id="date_on_draft_store" 
                                value="{{ old('date_on_draft_store', $extraSummary->date_on_draft_store ? $extraSummary->date_on_draft_store->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('date_on_draft_store')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Received LM Consumer -->
                        <div>
                            <label for="date_received_lm_consumer" class="block text-sm font-medium text-gray-700">
                                Date Received LM Consumer
                            </label>
                            <input type="date" name="date_received_lm_consumer" id="date_received_lm_consumer" 
                                value="{{ old('date_received_lm_consumer', $extraSummary->date_received_lm_consumer ? $extraSummary->date_received_lm_consumer->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('date_received_lm_consumer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Return SDC Billing -->
                        <div>
                            <label for="date_return_sdc_billing" class="block text-sm font-medium text-gray-700">
                                Date Return SDC Billing
                            </label>
                            <input type="date" name="date_return_sdc_billing" id="date_return_sdc_billing" 
                                value="{{ old('date_return_sdc_billing', $extraSummary->date_return_sdc_billing ? $extraSummary->date_return_sdc_billing->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('date_return_sdc_billing')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('ls.extra-summaries') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Extra Summary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
