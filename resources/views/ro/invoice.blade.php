@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" id="invoice">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Electricity Bill (Invoice)</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Bill No: <span class="font-semibold">{{ $bill->bill_no }}</span></p>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Issue Date: {{ $bill->issue_date?->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-300">Due Date: {{ $bill->due_date?->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <img src="{{ asset('images/mfn-logo.png') }}" alt="Logo" class="h-12 w-auto inline-block">
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        @if($bill->subdivision)
                            <div class="font-semibold">{{ $bill->subdivision->name }}</div>
                        @endif
                        <div>Meter Flow Nation (MEPCO)</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Consumer Details</h2>
                    <dl class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
                        <div class="flex justify-between"><dt>Consumer</dt><dd class="font-medium">{{ $bill->consumer->name ?? 'N/A' }}</dd></div>
                        <div class="flex justify-between"><dt>Subdivision</dt><dd class="font-medium">{{ $bill->subdivision->name ?? 'N/A' }}</dd></div>
                        <div class="flex justify-between"><dt>Meter No</dt><dd class="font-medium">{{ $bill->meter->meter_no ?? 'N/A' }}</dd></div>
                    </dl>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Billing Period</h2>
                    <dl class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
                        <div class="flex justify-between"><dt>Month</dt><dd class="font-medium">{{ str_pad($bill->billing_month, 2, '0', STR_PAD_LEFT) }}</dd></div>
                        <div class="flex justify-between"><dt>Year</dt><dd class="font-medium">{{ $bill->billing_year }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <tr>
                            <td class="px-6 py-4">Energy Charges</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->energy_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Fixed Charges</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->fixed_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">GST</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->gst_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">TV Fee</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->tv_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Meter Rent</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->meter_rent, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Late Payment Surcharge</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->late_payment_surcharge, 2) }}</td>
                        </tr>
                        <tr class="bg-gray-50 dark:bg-gray-700 font-semibold">
                            <td class="px-6 py-4">Total Amount</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Amount Paid</td>
                            <td class="px-6 py-4 text-right">{{ number_format($bill->amount_paid, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4">Payment Status</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bill->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                    {{ ucfirst($bill->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('ro.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">← Back to Dashboard</a>
                <button onclick="window.print()" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 py-2 rounded-lg">Print Invoice</button>
            </div>
        </div>
    </div>
</div>
@endsection
