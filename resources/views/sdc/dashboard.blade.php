@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Subdivision Selector -->
        <div class="flex justify-between items-center mb-6" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">SDC Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Welcome back, {{ Auth::user()->name }}</p>
            </div>
            
            <!-- Subdivision Switcher -->
            @if($subdivisions->count() > 1)
                <form action="{{ route('sdc.switch-subdivision') }}" method="POST" class="flex items-center">
                    @csrf
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Subdivision:</label>
                    <select name="subdivision_id" onchange="this.form.submit()" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-blue-500 transition">
                        @foreach($subdivisions as $sub)
                            <option value="{{ $sub->id }}" {{ $currentSubdivision && $currentSubdivision->id == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        <!-- Current Subdivision Info -->
        @if($currentSubdivision)
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $currentSubdivision->name }}</h2>
                        <p class="text-purple-100 mt-1">Code: {{ $currentSubdivision->code }}</p>
                        @if($currentSubdivision->company)
                            <p class="text-purple-100">{{ $currentSubdivision->company->name }}</p>
                        @endif
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Pending Applications</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Approved Applications</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $stats['approved_applications'] }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Global Summaries</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $stats['total_summaries'] }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Global Summaries Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8" data-aos="fade-up" data-aos-delay="200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Global Summaries</h2>
                <a href="{{ route('sdc.global-summaries') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    View All
                </a>
            </div>

            @if($globalSummaries->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Application No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Meter No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SIM Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($globalSummaries as $summary)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $summary->application_no }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $summary->customer_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $summary->meter_no ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $summary->sim_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $summary->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('sdc.edit-global-summary', $summary->id) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $globalSummaries->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No global summaries found for this subdivision.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

