@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold">Edit Tariff</h2>
                    <a href="{{ route('admin.tariffs.index') }}" class="text-gray-600 hover:text-gray-800">← Back</a>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.tariffs.update', $tariff) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Tariff Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $tariff->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $tariff->category) }}" 
                                required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="rate_per_unit" class="block text-sm font-medium text-gray-700">Rate per Unit (Rs.) *</label>
                            <input type="number" name="rate_per_unit" id="rate_per_unit" value="{{ old('rate_per_unit', $tariff->rate_per_unit) }}" 
                                step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="from_units" class="block text-sm font-medium text-gray-700">From Units *</label>
                            <input type="number" name="from_units" id="from_units" value="{{ old('from_units', $tariff->from_units) }}" 
                                required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="to_units" class="block text-sm font-medium text-gray-700">To Units</label>
                            <input type="number" name="to_units" id="to_units" value="{{ old('to_units', $tariff->to_units) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="fixed_charges" class="block text-sm font-medium text-gray-700">Fixed Charges (Rs.)</label>
                            <input type="number" name="fixed_charges" id="fixed_charges" value="{{ old('fixed_charges', $tariff->fixed_charges) }}" 
                                step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="gst_percentage" class="block text-sm font-medium text-gray-700">GST Percentage</label>
                            <input type="number" name="gst_percentage" id="gst_percentage" value="{{ old('gst_percentage', $tariff->gst_percentage) }}" 
                                step="0.01" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="tv_fee" class="block text-sm font-medium text-gray-700">TV Fee (Rs.)</label>
                            <input type="number" name="tv_fee" id="tv_fee" value="{{ old('tv_fee', $tariff->tv_fee) }}" 
                                step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="meter_rent" class="block text-sm font-medium text-gray-700">Meter Rent (Rs.)</label>
                            <input type="number" name="meter_rent" id="meter_rent" value="{{ old('meter_rent', $tariff->meter_rent) }}" 
                                step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="effective_from" class="block text-sm font-medium text-gray-700">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from', $tariff->effective_from) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="effective_to" class="block text-sm font-medium text-gray-700">Effective To</label>
                            <input type="date" name="effective_to" id="effective_to" value="{{ old('effective_to', $tariff->effective_to) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $tariff->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Tariff
                        </button>
                        <a href="{{ route('admin.tariffs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
