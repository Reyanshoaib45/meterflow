@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Summary Details</h2>
                    <a href="{{ route('ro.dashboard') }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300">
                        ← Back to Dashboard
                    </a>
                </div>

                <!-- Summary Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Information</h3>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter No</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $summary->meter_no ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">SIM Number</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $summary->sim_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- History Information -->
                @if($histories->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">History with SEO Number</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">SEO Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($histories as $history)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $history->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $history->seo_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ ucfirst($history->action_type) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $history->remarks ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('ro.manage-billing', $summary->id) }}" class="bg-orange-600 dark:bg-orange-700 hover:bg-orange-700 dark:hover:bg-orange-800 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Manage Billing
                    </a>
                    <a href="{{ route('ro.dashboard') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-lg font-semibold transition">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

