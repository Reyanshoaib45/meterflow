@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Bill Details</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.billing.edit', $bill) }}" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Edit
                        </a>
                        <a href="{{ route('admin.billing.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition">
                            Back
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bill Number</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $bill->bill_number }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Consumer</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->consumer->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter Number</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->meter->meter_no ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Subdivision</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->subdivision->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Previous Reading</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ number_format($bill->previous_reading, 2) }} kWh</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Current Reading</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ number_format($bill->current_reading, 2) }} kWh</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Units Consumed</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($bill->units_consumed, 2) }} kWh</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Rate Per Unit</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->rate_per_unit, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Energy Charges</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->energy_charges, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fixed Charges</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->fixed_charges, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">GST Amount</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->gst_amount, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">TV Fee</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->tv_fee, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter Rent</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">Rs. {{ number_format($bill->meter_rent, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">Rs. {{ number_format($bill->total_amount, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($bill->payment_status == 'paid') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @elseif($bill->payment_status == 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
                                {{ ucfirst($bill->payment_status) }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bill Date</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('d M, Y') : 'N/A' }}</p>
                    </div>

                    @if($bill->verifier)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Verified By</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->verifier->name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Verification Status</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($bill->is_verified) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 @endif">
                                {{ $bill->is_verified ? 'Verified' : 'Not Verified' }}
                            </span>
                        </p>
                    </div>

                    @if($bill->remarks)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Remarks</label>
                        <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $bill->remarks }}</p>
                    </div>
                    @endif

                    <div class="md:col-span-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div>
                                <span class="font-medium">Created:</span> {{ $bill->created_at->format('d M, Y H:i') }}
                            </div>
                            <div>
                                <span class="font-medium">Last Updated:</span> {{ $bill->updated_at->format('d M, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

