@extends('layouts.app')

@section('content')
<div class="py-12 page-transition">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 animate-fade-in-down">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Extra Summaries</h2>
                        <p class="text-gray-600 mt-1">Manage additional application summaries for your subdivision</p>
                    </div>
                    
                    <div class="flex gap-2 items-center">
                        @if($subdivisions->count() > 1)
                            <form action="{{ route('ls.switch-subdivision') }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <label class="text-sm font-medium text-gray-700">Subdivision:</label>
                                <select name="subdivision_id" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach($subdivisions as $subdivision)
                                        <option value="{{ $subdivision->id }}" {{ $subdivision->id == $currentSubdivisionId ? 'selected' : '' }}>
                                            {{ $subdivision->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif
                        
                        <a href="{{ route('ls.create-extra-summary') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 alert" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Extra Summaries Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in-up">
            <div class="p-6 text-gray-900">
                @if($extraSummaries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Application No
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer Name
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meter No
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SIM Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Draft Store Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Received LM Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mobile No
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SC No
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Return SDC Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($extraSummaries as $summary)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $summary->application_no }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $summary->customer_name }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $summary->meter_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $summary->sim_date ? \Carbon\Carbon::parse($summary->sim_date)->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $summary->date_on_draft_store ? \Carbon\Carbon::parse($summary->date_on_draft_store)->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $summary->date_received_lm_consumer ? \Carbon\Carbon::parse($summary->date_received_lm_consumer)->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $summary->customer_mobile_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $summary->customer_sc_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $summary->date_return_sdc_billing ? \Carbon\Carbon::parse($summary->date_return_sdc_billing)->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('ls.edit-extra-summary', $summary->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $extraSummaries->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No extra summaries</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new extra summary.</p>
                        <div class="mt-6">
                            <a href="{{ route('ls.create-extra-summary') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Extra Summary
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
