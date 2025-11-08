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
            ['name' => 'Edit', 'url' => '']
        ]" />
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Meter</h2>
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

                <form action="{{ route('admin.meters.update', $meter) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="meter_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meter Number *</label>
                            <input type="text" name="meter_no" id="meter_no" value="{{ old('meter_no', $meter->meter_no) }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="subdivision_id" class="block text-sm font-medium text-gray-700">Subdivision *</label>
                            <select name="subdivision_id" id="subdivision_id" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select Subdivision</option>
                                @foreach($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}" {{ old('subdivision_id', $meter->subdivision_id) == $subdivision->id ? 'selected' : '' }}>
                                        {{ $subdivision->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="meter_make" class="block text-sm font-medium text-gray-700">Meter Make</label>
                            <input type="text" name="meter_make" id="meter_make" value="{{ old('meter_make', $meter->meter_make) }}"
                                placeholder="e.g., ABB, Siemens, Schneider"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="reading" class="block text-sm font-medium text-gray-700">Current Reading</label>
                            <input type="number" name="reading" id="reading" value="{{ old('reading', $meter->reading) }}" step="0.01"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="sim_number" class="block text-sm font-medium text-gray-700">SIM Number</label>
                            <input type="text" name="sim_number" id="sim_number" value="{{ old('sim_number', $meter->sim_number) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="installed_on" class="block text-sm font-medium text-gray-700">Installation Date</label>
                            <input type="date" name="installed_on" id="installed_on" value="{{ old('installed_on', $meter->installed_on) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="last_reading" class="block text-sm font-medium text-gray-700">Last Reading</label>
                            <input type="number" name="last_reading" id="last_reading" value="{{ old('last_reading', $meter->last_reading) }}" step="0.01"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="last_reading_date" class="block text-sm font-medium text-gray-700">Last Reading Date</label>
                            <input type="date" name="last_reading_date" id="last_reading_date" value="{{ old('last_reading_date', $meter->last_reading_date) }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label for="meter_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meter Image</label>
                            @if($meter->meter_image)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                                    <img src="{{ Storage::url($meter->meter_image) }}" alt="Meter Image" class="max-w-xs rounded-lg shadow-md">
                                </div>
                            @endif
                            <input type="file" name="meter_image" id="meter_image" accept="image/*"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a new image to replace the current one (JPG, PNG, max 5MB)</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('remarks', $meter->remarks) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Meter
                        </button>
                        <a href="{{ route('admin.meters.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
