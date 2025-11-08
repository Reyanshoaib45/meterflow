@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Meters', 'url' => route('admin.meters.index')],
            ['name' => 'View Details', 'url' => '']
        ]" />
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Meter Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.meters.assign', $meter) }}" class="bg-green-500 dark:bg-green-600 hover:bg-green-700 dark:hover:bg-green-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Assign to Customer
                        </a>
                        <a href="{{ route('admin.meters.edit', $meter) }}" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Edit
                        </a>
                        <a href="{{ route('admin.meters.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition">
                            Back
                        </a>
                    </div>
                </div>

                @if($meter->meter_image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Meter Image</label>
                        <img src="{{ Storage::url($meter->meter_image) }}" alt="Meter Image" class="max-w-md rounded-lg shadow-lg">
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter Number</label>
                        <p class="mt-1 text-lg font-semibold">{{ $meter->meter_no }}</p>
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
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Application No</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">
                            @if($meter->application)
                                <a href="{{ route('admin.applications.edit', $meter->application) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    {{ $meter->application->application_no }}
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">In Store</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                @if($meter->in_store) bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @endif">
                                {{ $meter->in_store ? 'Yes (In Store)' : 'No (Assigned)' }}
                            </span>
                        </p>
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
