@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Meter Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.meters.edit', $meter) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin.meters.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Meter Number</label>
                        <p class="mt-1 text-lg font-semibold">{{ $meter->meter_no }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($meter->status == 'active') bg-green-100 text-green-800 
                                @elseif($meter->status == 'disconnected') bg-red-100 text-red-800 
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($meter->status) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Consumer</label>
                        <p class="mt-1 text-lg">{{ $meter->consumer->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Consumer CNIC</label>
                        <p class="mt-1 text-lg">{{ $meter->consumer->cnic ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Subdivision</label>
                        <p class="mt-1 text-lg">{{ $meter->subdivision->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Meter Make</label>
                        <p class="mt-1 text-lg">{{ $meter->meter_make ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Current Reading</label>
                        <p class="mt-1 text-lg font-semibold">{{ number_format($meter->reading, 2) }} kWh</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">SIM Number</label>
                        <p class="mt-1 text-lg">{{ $meter->sim_number ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Installation Date</label>
                        <p class="mt-1 text-lg">{{ $meter->installed_on ? \Carbon\Carbon::parse($meter->installed_on)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Last Reading</label>
                        <p class="mt-1 text-lg">{{ $meter->last_reading ? number_format($meter->last_reading, 2) . ' kWh' : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Last Reading Date</label>
                        <p class="mt-1 text-lg">{{ $meter->last_reading_date ? \Carbon\Carbon::parse($meter->last_reading_date)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Application ID</label>
                        <p class="mt-1 text-lg">{{ $meter->application_id ?? 'N/A' }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500">Remarks</label>
                        <p class="mt-1 text-lg">{{ $meter->remarks ?? 'N/A' }}</p>
                    </div>

                    <div class="md:col-span-2 pt-4 border-t">
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Created:</span> {{ $meter->created_at->format('d M, Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">Last Updated:</span> {{ $meter->updated_at->format('d M, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
