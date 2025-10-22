@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold">Consumer History</h2>
                        <p class="text-gray-600 mt-1">{{ $consumer->name }}</p>
                    </div>
                    <a href="{{ route('admin.consumers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Back
                    </a>
                </div>

                <!-- Consumer Info Card -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">CNIC</label>
                            <p class="font-semibold">{{ $consumer->cnic }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Phone</label>
                            <p class="font-semibold">{{ $consumer->phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Status</label>
                            <p>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($consumer->status == 'active') bg-green-100 text-green-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($consumer->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bills History -->
                <h3 class="text-lg font-semibold mb-4">Billing History</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bill No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month/Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bills as $bill)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $bill->bill_no }}</td>
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
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($bill->due_date)->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No bills found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Complaints History -->
                <h3 class="text-lg font-semibold mb-4">Complaints History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Complaint ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($complaints as $complaint)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $complaint->complaint_id }}</td>
                                    <td class="px-6 py-4">{{ Str::limit($complaint->subject, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($complaint->status == 'resolved') bg-green-100 text-green-800
                                            @elseif($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $complaint->created_at->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No complaints found.</td>
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
