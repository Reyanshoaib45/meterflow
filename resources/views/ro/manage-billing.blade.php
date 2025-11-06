@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Summary Preview</h1>
                    @if($currentSubdivision)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Subdivision: {{ $currentSubdivision->name }} ({{ $currentSubdivision->code }})
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('ro.view-summary', $summary->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View Full Summary</a>
                    <a href="{{ route('ro.dashboard') }}" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300">← Back to Dashboard</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Application Info</h2>
                    <dl class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
                        <div class="flex justify-between"><dt>Application No</dt><dd class="font-medium">{{ $summary->application_no }}</dd></div>
                        <div class="flex justify-between"><dt>Customer</dt><dd class="font-medium">{{ $summary->customer_name }}</dd></div>
                        <div class="flex justify-between"><dt>Address</dt><dd class="font-medium">{{ $summary->consumer_address ?? 'N/A' }}</dd></div>
                        <div class="flex justify-between"><dt>Assigned RO</dt><dd class="font-medium">{{ optional($summary->application->assignedRO)->name ?? 'N/A' }}</dd></div>
                        @if($summary->application && $summary->application->subdivision)
                        <div class="flex justify-between"><dt>Subdivision</dt><dd class="font-medium">{{ $summary->application->subdivision->name }}</dd></div>
                        @endif
                    </dl>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Meter Details (SDC Filled)</h2>
                    @php($meter = optional($summary->application)->meter)
                    <dl class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
                        <div class="flex justify-between"><dt>Meter No</dt><dd class="font-medium">{{ $meter->meter_no ?? ($summary->meter_no ?? 'N/A') }}</dd></div>
                        <div class="flex justify-between"><dt>SIM Number</dt><dd class="font-medium">{{ $meter->sim_number ?? ($summary->sim_number ?? 'N/A') }}</dd></div>
                        <div class="flex justify-between"><dt>SEO Number</dt><dd class="font-medium">{{ $meter->seo_number ?? 'N/A' }}</dd></div>
                        <div class="flex justify-between"><dt>Installed On</dt><dd class="font-medium">{{ optional($meter->installed_on)->format('Y-m-d') ?? 'N/A' }}</dd></div>
                        <div class="flex justify-between"><dt>Status</dt><dd class="font-medium">{{ $meter->status ?? 'N/A' }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('ro.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">← Back to Dashboard</a>
                <a href="{{ route('ro.view-summary', $summary->id) }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold px-4 py-2 rounded-lg">Open Full Summary</a>
            </div>
        </div>
    </div>
</div>
@endsection

