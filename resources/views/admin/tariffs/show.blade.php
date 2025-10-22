@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Tariff Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.tariffs.edit', $tariff) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit
                        </a>
                        <a href="{{ route('admin.tariffs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tariff Name</label>
                        <p class="mt-1 text-lg font-semibold">{{ $tariff->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Category</label>
                        <p class="mt-1 text-lg">{{ $tariff->category }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Units Range</label>
                        <p class="mt-1 text-lg font-semibold">{{ $tariff->from_units }} - {{ $tariff->to_units ?? '∞' }} kWh</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Rate per Unit</label>
                        <p class="mt-1 text-lg font-semibold text-blue-600">Rs. {{ number_format($tariff->rate_per_unit, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Fixed Charges</label>
                        <p class="mt-1 text-lg">Rs. {{ number_format($tariff->fixed_charges ?? 0, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">GST Percentage</label>
                        <p class="mt-1 text-lg">{{ number_format($tariff->gst_percentage ?? 0, 2) }}%</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">TV Fee</label>
                        <p class="mt-1 text-lg">Rs. {{ number_format($tariff->tv_fee ?? 0, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Meter Rent</label>
                        <p class="mt-1 text-lg">Rs. {{ number_format($tariff->meter_rent ?? 0, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($tariff->is_active)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Effective From</label>
                        <p class="mt-1 text-lg">{{ $tariff->effective_from ? \Carbon\Carbon::parse($tariff->effective_from)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Effective To</label>
                        <p class="mt-1 text-lg">{{ $tariff->effective_to ? \Carbon\Carbon::parse($tariff->effective_to)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div class="md:col-span-2 pt-4 border-t">
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Created:</span> {{ $tariff->created_at->format('d M, Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">Last Updated:</span> {{ $tariff->updated_at->format('d M, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
