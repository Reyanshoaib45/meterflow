@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Manage Billing</h2>
                    <a href="{{ route('ro.view-summary', $summary->id) }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300">
                        ← Back to Summary
                    </a>
                </div>

                <!-- Summary Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Application No</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $summary->application_no }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Customer Name</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $summary->customer_name }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Consumer Address</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $summary->consumer_address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Create New Bill Form -->
                @if($consumer && $meter)
                <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create New Bill</h3>
                    <form action="{{ route('ro.create-bill', $summary->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="billing_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Billing Month *</label>
                                <select name="billing_month" id="billing_month" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Select Month</option>
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $month)
                                        <option value="{{ $month }}" {{ old('billing_month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="billing_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Billing Year *</label>
                                <input type="number" name="billing_year" id="billing_year" value="{{ old('billing_year', date('Y')) }}" required min="2020" max="2100"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label for="previous_reading" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Previous Reading *</label>
                                <input type="number" name="previous_reading" id="previous_reading" value="{{ old('previous_reading', $meter->last_reading ?? 0) }}" required step="0.01" min="0"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label for="current_reading" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Reading *</label>
                                <input type="number" name="current_reading" id="current_reading" value="{{ old('current_reading') }}" required step="0.01" min="0"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date *</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label for="issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issue Date *</label>
                                <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                                Create Bill
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 mb-6">
                    <p class="text-yellow-800 dark:text-yellow-200">Consumer or meter not found for this application. Please ensure the application has a consumer and meter assigned.</p>
                </div>
                @endif

                <!-- Existing Bills -->
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Existing Bills</h3>
                    @if($bills && $bills->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bill No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Month/Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Units</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($bills as $bill)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $bill->bill_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $bill->billing_month }} {{ $bill->billing_year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $bill->units_consumed }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Rs. {{ number_format($bill->total_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($bill->payment_status == 'paid') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                    @elseif($bill->payment_status == 'overdue') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                    @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                    @endif">
                                                    {{ ucfirst($bill->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $bill->due_date->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No bills found for this consumer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

