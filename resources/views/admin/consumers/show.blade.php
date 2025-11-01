@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Consumer Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.consumers.edit', $consumer) }}" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Edit
                        </a>
                        <a href="{{ route('admin.consumers.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition">
                            ← Back to Consumers
                        </a>
                    </div>
                </div>

                <!-- Consumer Information -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Consumer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Consumer ID</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->consumer_id ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Name</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">CNIC</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->cnic }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Phone</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->phone }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Email</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->email ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Subdivision</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->subdivision->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Connection Type</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ ucfirst($consumer->connection_type) }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status</label>
                            <div>
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @if($consumer->status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @elseif($consumer->status == 'disconnected') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                    @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                    @endif">
                                    {{ ucfirst($consumer->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Address</label>
                            <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $consumer->address }}</div>
                        </div>
                    </div>
                </div>

                <!-- Meters -->
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Associated Meters</h3>
                    @if($consumer->meters && $consumer->meters->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Meter No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Application No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Make</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">In Store</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($consumer->meters as $meter)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $meter->meter_no }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $meter->application->application_no ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $meter->meter_make ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($meter->status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                    @elseif($meter->status == 'faulty') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                                    @elseif($meter->status == 'disconnected') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                                    @endif">
                                                    {{ ucfirst($meter->status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($meter->in_store) bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                                    @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                                    @endif">
                                                    {{ $meter->in_store ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.meters.show', $meter) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">View</a>
                                                <a href="{{ route('admin.meters.assign', $meter) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Assign</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No meters assigned to this consumer.</p>
                    @endif
                </div>

                <!-- Bills Summary -->
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bills Summary</h3>
                        <a href="{{ route('admin.consumers.history', $consumer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View All History →</a>
                    </div>
                    @if($consumer->bills && $consumer->bills->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bills</div>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $consumer->bills->count() }}</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid</div>
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $consumer->bills->where('payment_status', 'paid')->count() }}</div>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Unpaid</div>
                                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $consumer->bills->where('payment_status', 'unpaid')->count() }}</div>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</div>
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">Rs. {{ number_format($consumer->bills->sum('total_amount'), 2) }}</div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No bills found for this consumer.</p>
                    @endif
                </div>

                <!-- Complaints -->
                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Complaints</h3>
                        <a href="{{ route('admin.consumers.history', $consumer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">View All →</a>
                    </div>
                    @if($consumer->complaints && $consumer->complaints->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($consumer->complaints->take(5) as $complaint)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $complaint->subject ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                    {{ ucfirst($complaint->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $complaint->created_at->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No complaints found for this consumer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

