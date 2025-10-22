@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold">Edit Meter</h2>
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

                <form action="{{ route('admin.meters.update', $meter) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="meter_no" class="block text-sm font-medium text-gray-700">Meter Number *</label>
                            <input type="text" name="meter_no" id="meter_no" value="{{ old('meter_no', $meter->meter_no) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="consumer_id" class="block text-sm font-medium text-gray-700">Consumer *</label>
                            <select name="consumer_id" id="consumer_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select Consumer</option>
                                @foreach($consumers as $consumer)
                                    <option value="{{ $consumer->id }}" {{ old('consumer_id', $meter->consumer_id) == $consumer->id ? 'selected' : '' }}>
                                        {{ $consumer->name }} ({{ $consumer->cnic }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="subdivision_id" class="block text-sm font-medium text-gray-700">Subdivision *</label>
                            <select name="subdivision_id" id="subdivision_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="reading" class="block text-sm font-medium text-gray-700">Current Reading</label>
                            <input type="number" name="reading" id="reading" value="{{ old('reading', $meter->reading) }}" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="sim_number" class="block text-sm font-medium text-gray-700">SIM Number</label>
                            <input type="text" name="sim_number" id="sim_number" value="{{ old('sim_number', $meter->sim_number) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="active" {{ old('status', $meter->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="faulty" {{ old('status', $meter->status) == 'faulty' ? 'selected' : '' }}>Faulty</option>
                                <option value="disconnected" {{ old('status', $meter->status) == 'disconnected' ? 'selected' : '' }}>Disconnected</option>
                            </select>
                        </div>

                        <div>
                            <label for="installed_on" class="block text-sm font-medium text-gray-700">Installation Date</label>
                            <input type="date" name="installed_on" id="installed_on" value="{{ old('installed_on', $meter->installed_on) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="last_reading" class="block text-sm font-medium text-gray-700">Last Reading</label>
                            <input type="number" name="last_reading" id="last_reading" value="{{ old('last_reading', $meter->last_reading) }}" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="last_reading_date" class="block text-sm font-medium text-gray-700">Last Reading Date</label>
                            <input type="date" name="last_reading_date" id="last_reading_date" value="{{ old('last_reading_date', $meter->last_reading_date) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('remarks', $meter->remarks) }}</textarea>
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
