@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold">Consumer Applications</h2>
                        <p class="text-gray-600 mt-1">{{ $consumer->name }} - {{ $consumer->cnic }}</p>
                    </div>
                    <a href="{{ route('admin.consumers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Back
                    </a>
                </div>

                <!-- Consumer Info Card -->
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Phone</label>
                            <p class="font-semibold">{{ $consumer->phone }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Connection Type</label>
                            <p class="font-semibold">{{ $consumer->connection_type }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Subdivision</label>
                            <p class="font-semibold">{{ $consumer->subdivision->name ?? 'N/A' }}</p>
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

                <!-- Applications List -->
                <h3 class="text-lg font-semibold mb-4">All Applications</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meter Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Connection Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $application->application_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->meter_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->connection_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($application->status == 'approved') bg-green-100 text-green-800 
                                            @elseif($application->status == 'rejected') bg-red-100 text-red-800 
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $application->fee_amount ? 'Rs. ' . number_format($application->fee_amount, 2) : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->created_at->format('d M, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.applications.edit', $application) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        <a href="{{ route('admin.applications.history', $application) }}" class="text-blue-600 hover:text-blue-900">
                                            History
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No applications found for this consumer.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($applications->count() > 0)
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
