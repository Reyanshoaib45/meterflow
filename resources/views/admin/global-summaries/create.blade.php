@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Create Global Summary</h2>

                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">There were some errors:</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
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
                            <label for="application_id" class="block text-sm font-medium text-gray-700">Application</label>
                            <select name="application_id" id="application_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Select an Application</option>
                                @foreach($applications as $application)
                                    <option value="{{ $application->id }}" {{ old('application_id') == $application->id ? 'selected' : '' }}>
                                        {{ $application->application_no }} - {{ $application->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SIM Date -->
                        <div>
                            <label for="sim_date" class="block text-sm font-medium text-gray-700">SIM Date</label>
                            <input type="date" name="sim_date" id="sim_date" value="{{ old('sim_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Date on Draft on Store -->
                        <div>
                            <label for="date_on_draft_store" class="block text-sm font-medium text-gray-700">Date on Draft on Store</label>
                            <input type="date" name="date_on_draft_store" id="date_on_draft_store" value="{{ old('date_on_draft_store') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Date of Received by LM/Consumer -->
                        <div>
                            <label for="date_received_lm_consumer" class="block text-sm font-medium text-gray-700">Date of Received by LM/Consumer</label>
                            <input type="date" name="date_received_lm_consumer" id="date_received_lm_consumer" value="{{ old('date_received_lm_consumer') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Customer Mobile No -->
                        <div>
                            <label for="customer_mobile_no" class="block text-sm font-medium text-gray-700">Customer Mobile No</label>
                            <input type="text" name="customer_mobile_no" id="customer_mobile_no" value="{{ old('customer_mobile_no') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Customer SC No -->
                        <div>
                            <label for="customer_sc_no" class="block text-sm font-medium text-gray-700">Customer SC No</label>
                            <input type="text" name="customer_sc_no" id="customer_sc_no" value="{{ old('customer_sc_no') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <!-- Date of Return to SDC for Billing -->
                        <div>
                            <label for="date_return_sdc_billing" class="block text-sm font-medium text-gray-700">Date of Return to SDC for Billing</label>
                            <input type="date" name="date_return_sdc_billing" id="date_return_sdc_billing" value="{{ old('date_return_sdc_billing') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.global-summaries.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Global Summary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection