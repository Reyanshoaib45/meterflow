@extends('layouts.app')

@section('content')
<div class="py-12 page-transition">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 animate-fade-in-down">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Meter Store</h2>
                        <p class="text-gray-600 mt-1">Manage and view all meters in your subdivision</p>
                    </div>
                    
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
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 stagger-children">
            <!-- Total Meters -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Meters</p>
                        <p class="text-3xl font-bold text-gray-900 stat-number">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Meters -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Meters</p>
                        <p class="text-3xl font-bold text-gray-900 stat-number">{{ $stats['active'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Faulty Meters -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Faulty Meters</p>
                        <p class="text-3xl font-bold text-gray-900 stat-number">{{ $stats['faulty'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Disconnected Meters -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500 hover-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Disconnected</p>
                        <p class="text-3xl font-bold text-gray-900 stat-number">{{ $stats['disconnected'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meters Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in-up">
            <div class="p-6 text-gray-900">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">All Meters</h3>
                    
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('ls.meter-store') }}" class="flex gap-2">
                        <input type="text" name="search" placeholder="Search meters..." 
                            value="{{ request('search') }}"
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Search
                        </button>
                    </form>
                </div>

                @if($meters->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meter No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Application No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Consumer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Make
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reading
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Installed On
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($meters as $meter)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $meter->meter_no }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $meter->application->application_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $meter->consumer->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $meter->meter_make }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $meter->reading }} kWh
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($meter->status == 'active') bg-green-100 text-green-800
                                                @elseif($meter->status == 'faulty') bg-yellow-100 text-yellow-800
                                                @elseif($meter->status == 'disconnected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($meter->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $meter->installed_on ? \Carbon\Carbon::parse($meter->installed_on)->format('d M, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.meters.show', $meter->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $meters->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No meters found</h3>
                        <p class="mt-1 text-sm text-gray-500">No meters are registered in this subdivision yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
