@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold">Edit Application</h2>
                    <a href="{{ route('admin.applications') }}" class="text-gray-600 hover:text-gray-800">← Back</a>
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

                <form action="{{ route('admin.applications.update', $application) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Number</label>
                            <p class="text-lg font-semibold">{{ $application->application_no }}</p>
                        </div>

                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $application->customer_name) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                        </div>

                        <div>
                            <label for="cnic" class="block text-sm font-medium text-gray-700">CNIC</label>
                            <input type="text" name="cnic" id="cnic" value="{{ old('cnic', $application->cnic) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $application->phone) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                        </div>

                        <div>
                            <label for="connection_type" class="block text-sm font-medium text-gray-700">Connection Type</label>
                            <input type="text" name="connection_type" id="connection_type" value="{{ old('connection_type', $application->connection_type) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="2" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('address', $application->address) }}</textarea>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                <option value="pending" {{ old('status', $application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $application->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="closed" {{ old('status', $application->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label for="fee_amount" class="block text-sm font-medium text-gray-700">Fee Amount</label>
                            <input type="number" name="fee_amount" id="fee_amount" value="{{ old('fee_amount', $application->fee_amount) }}" 
                                step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label for="meter_number" class="block text-sm font-medium text-gray-700">Meter Number</label>
                            <input type="text" name="meter_number" id="meter_number" value="{{ old('meter_number', $application->meter_number) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            <p id="meter-status" class="mt-1 text-sm"></p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">{{ old('remarks') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Add any notes about this status change</p>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Application
                        </button>
                        <a href="{{ route('admin.applications') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const meterInput = document.getElementById('meter_number');
    const meterStatus = document.getElementById('meter-status');
    let debounceTimer;

    if (meterInput) {
        meterInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const meterNumber = this.value.trim();
            
            if (meterNumber.length === 0) {
                meterStatus.textContent = '';
                meterStatus.className = 'mt-1 text-sm';
                return;
            }

            meterStatus.textContent = 'Checking...';
            meterStatus.className = 'mt-1 text-sm text-gray-500';

            debounceTimer = setTimeout(() => {
                fetch(`/api/check-meter/${encodeURIComponent(meterNumber)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        meterStatus.textContent = `✓ Meter found - Assigned to: ${data.consumer || 'Unknown'}`;
                        meterStatus.className = 'mt-1 text-sm text-green-600';
                    } else {
                        meterStatus.textContent = '✗ Meter number not found in system';
                        meterStatus.className = 'mt-1 text-sm text-red-600';
                    }
                })
                .catch(error => {
                    meterStatus.textContent = '⚠ Error checking meter number';
                    meterStatus.className = 'mt-1 text-sm text-orange-600';
                    console.error('Error:', error);
                });
            }, 500);
        });
    }
});
</script>
@endsection
