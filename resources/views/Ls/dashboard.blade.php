@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Subdivision Selector -->
        <div class="flex justify-between items-center mb-6" data-aos="fade-down">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">LS Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Welcome back, {{ Auth::user()->name }}</p>
            </div>
            
            <!-- Subdivision Switcher -->
            @if($subdivisions->count() > 1)
                <form action="{{ route('ls.switch-subdivision') }}" method="POST" class="flex items-center">
                    @csrf
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Subdivision:</label>
                    <select name="subdivision_id" onchange="this.form.submit()" 
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 mb-8 text-white" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $currentSubdivision->name }}</h2>
                        <p class="text-green-100 mt-1">Code: {{ $currentSubdivision->code }}</p>
                        @if($currentSubdivision->company)
                            <p class="text-green-100">{{ $currentSubdivision->company->name }}</p>
                        @endif
                    </div>
                    <div class="bg-white bg-opacity-20 p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <!-- Total Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Applications</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-yellow-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Pending</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_applications'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Approved</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['approved_applications'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Meters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Meters</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_meters'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active: {{ $stats['active_meters'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8" data-aos="fade-up">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('ls.applications', $currentSubdivision->id ?? 0) }}" 
                       class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition hover:shadow-md hover:-translate-y-1">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Applications</span>
                    </a>

                    <a href="{{ route('ls.extra-summaries') }}" 
                       class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition hover:shadow-md hover:-translate-y-1">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Extra Summaries</span>
                    </a>

                    <a href="{{ route('ls.meter-store') }}" 
                       class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition hover:shadow-md hover:-translate-y-1">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Meter Store</span>
                    </a>

                    <a href="{{ route('admin.global-summaries.index') }}" 
                       class="flex flex-col items-center p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-800 transition hover:shadow-md hover:-translate-y-1">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Global Summaries</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow" data-aos="fade-right">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Applications</h3>
                    <a href="{{ route('ls.applications', $currentSubdivision->id ?? 0) }}" 
                       class="text-sm text-green-600 hover:text-green-700 font-medium">View All →</a>
                </div>
                <div class="p-6">
                    @if($recentApplications && $recentApplications->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentApplications->take(5) as $app)
                                <div class="flex items-center justify-between pb-3 border-b last:border-0">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $app->application_no }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $app->customer_name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $app->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($app->status == 'approved') bg-green-100 text-green-800 
                                        @elseif($app->status == 'rejected') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No recent applications</p>
                    @endif
                </div>
            </div>

            <!-- Application Status Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Application Status Overview</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($statusChart as $status)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($status->status) }}</span>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $status->count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full 
                                        @if($status->status == 'approved') bg-green-600 
                                        @elseif($status->status == 'rejected') bg-red-600 
                                        @else bg-yellow-600 @endif" 
                                        style="width: {{ $stats['total_applications'] > 0 ? ($status->count / $stats['total_applications'] * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bills/Invoices Section for SDO -->
        @if(isset($recentBills) && $recentBills->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6" data-aos="fade-up" data-aos-delay="300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Bills/Invoices (SDO)</h3>
                    <div class="flex gap-2">
                        <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                            Total: {{ $stats['total_bills'] ?? 0 }}
                        </span>
                        <span class="text-xs px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full">
                            Pending: {{ $stats['pending_bills'] ?? 0 }}
                        </span>
                        <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                            Paid: {{ $stats['paid_bills'] ?? 0 }}
                        </span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bill No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Consumer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Meter No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Month/Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentBills->take(10) as $bill)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $bill->bill_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $bill->consumer->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $bill->meter->meter_no ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $bill->billing_month }} {{ $bill->billing_year }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        Rs. {{ number_format($bill->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($bill->payment_status == 'paid') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                            @elseif($bill->payment_status == 'overdue') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                            @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @endif">
                                            {{ ucfirst($bill->payment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Activity -->
        @if($recentActivity && $recentActivity->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentActivity->take(5) as $activity)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-medium">{{ $activity->application->application_no ?? 'N/A' }}</span>
                                        - {{ $activity->remarks }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
