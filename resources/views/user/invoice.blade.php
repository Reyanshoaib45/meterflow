@extends('layouts.app')

@section('title', 'Application Invoice')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Invoice Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-6">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Invoice</h1>
                    <p class="text-gray-600 dark:text-gray-400">Application Fee Invoice</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Date</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ now()->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-t border-b border-gray-200 dark:border-gray-700 py-6">
                <!-- From -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">From</h3>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->company->name ?? 'MEPCO' }}</p>
                    @if($application->subdivision)
                    <p class="text-gray-600 dark:text-gray-400">{{ $application->subdivision->name }}</p>
                    @endif
                </div>

                <!-- To -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Bill To</h3>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $application->customer_name }}</p>
                    @if($application->address)
                    <p class="text-gray-600 dark:text-gray-400">{{ $application->address }}</p>
                    @endif
                    @if($application->phone)
                    <p class="text-gray-600 dark:text-gray-400">Phone: {{ $application->phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Application Details -->
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-4">Application Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Application Number</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->application_no }}</p>
                    </div>
                    @if($application->meter_number)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Meter Number</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $application->meter_number }}</p>
                    </div>
                    @endif
                    @if($application->connection_type)
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Connection Type</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($application->connection_type) }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Fee Details -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Description</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-4 px-4 text-gray-900 dark:text-white">
                                <p class="font-semibold">Application Fee</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Connection application processing fee</p>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">Rs. {{ number_format($application->fee_amount, 2) }}</p>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                            <td class="py-4 px-4">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</p>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rs. {{ number_format($application->fee_amount, 2) }}</p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-3">Payment Instructions</h3>
                <ul class="space-y-2 text-blue-800 dark:text-blue-300">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Please pay this invoice amount at the designated MEPCO office or through authorized payment channels.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Bring this invoice and your application number for payment reference.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>After payment, your application will proceed to the next stage.</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="window.print()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Invoice
                </button>
                <a href="{{ route('track') }}?application_no={{ $application->application_no }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl text-center">
                    Back to Tracking
                </a>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            <p>This is an official invoice generated by Meter Flow Nation (MEPCO).</p>
            <p class="mt-1">For any queries, please contact your subdivision office.</p>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-4xl, .max-w-4xl * {
        visibility: visible;
    }
    .max-w-4xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    button, a {
        display: none !important;
    }
}
</style>
@endsection

