@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Billing', 'url' => '']
        ]" />
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Bills</h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_bills'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Paid Bills</h3>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['paid_bills'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Revenue</h3>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">Rs. {{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pending Amount</h3>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">Rs. {{ number_format($stats['pending_amount'], 2) }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 text-gray-900 dark:text-white">
                <h2 class="text-2xl font-semibold mb-6">Billing Management</h2>

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                    <form method="GET" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by bill no, consumer..." 
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <select name="subdivision" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="">All Subdivisions</option>
                            @foreach($subdivisions as $subdivision)
                                <option value="{{ $subdivision->id }}">{{ $subdivision->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                            <option value="">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors">Filter</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bill No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Consumer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Month/Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Units</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" data-infinite-scroll data-next-page="{{ $bills->nextPageUrl() }}">
                            @forelse($bills as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $bill->bill_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->consumer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $bill->billing_month }} {{ $bill->billing_year }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($bill->units_consumed, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rs. {{ number_format($bill->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($bill->payment_status == 'paid') bg-green-100 text-green-800
                                            @elseif($bill->payment_status == 'overdue') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($bill->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.billing.show', $bill) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No bills found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
