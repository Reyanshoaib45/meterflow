@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Assign Meter to Customer</h2>
                    <a href="{{ route('admin.meters.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        ← Back to Meters
                    </a>
                </div>

                <!-- Meter Info -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Meter Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter Number</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $meter->meter_no }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Meter Make</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $meter->meter_make ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Subdivision</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $meter->subdivision->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <div class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($meter->in_store) bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                    @else bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                    @endif">
                                    {{ $meter->in_store ? 'In Store' : 'Assigned' }}
                                </span>
                            </div>
                        </div>
                        @if($meter->application)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Current Application</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">
                                {{ $meter->application->application_no }} - {{ $meter->application->customer_name }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were errors with your submission</h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.meters.store-assignment', $meter->id) }}" class="space-y-6">
                    @csrf

                    <!-- Assign to Application -->
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assign to Application</h3>
                        <div class="mb-4">
                            <label for="application_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Application</label>
                            
                            <!-- Search Application by Number -->
                            <div class="mb-3">
                                <label for="application_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search by Application Number</label>
                                <div class="flex gap-2">
                                    <input type="text" id="application_search" placeholder="Enter application number (e.g., APP-MEPCO-SUB1-6125)"
                                        class="flex-1 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <button type="button" id="search-application-btn" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Search
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">If application is not in the list below, search by application number</p>
                            </div>
                            
                            <select name="application_id" id="application_id" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select Application *</option>
                                @foreach($applications as $application)
                                    <option value="{{ $application->id }}" data-cnic="{{ $application->cnic }}" {{ old('application_id') == $application->id || $meter->application_id == $application->id ? 'selected' : '' }}>
                                        {{ $application->application_no }} - {{ $application->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select an approved application to assign this meter (Required)</p>
                            
                            <!-- Application Details Display -->
                            <div id="application-details" class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hidden">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Application Details:</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Application No:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2" id="app-no">-</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Customer Name:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2" id="app-name">-</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">CNIC:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2" id="app-cnic">-</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Phone:</span>
                                        <span class="font-medium text-gray-900 dark:text-white ml-2" id="app-phone">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assign to Consumer -->
                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assign to Consumer</h3>
                        <div class="mb-4">
                            <label for="consumer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Consumer</label>
                            <select name="consumer_id" id="consumer_id"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select Consumer (Optional)</option>
                                @foreach($consumers as $consumer)
                                    <option value="{{ $consumer->id }}" data-cnic="{{ $consumer->cnic }}" {{ old('consumer_id') == $consumer->id || $meter->consumer_id == $consumer->id ? 'selected' : '' }}>
                                        {{ $consumer->name }} ({{ $consumer->consumer_id ?? $consumer->cnic }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select a consumer to assign this meter (will auto-fill if application is selected)</p>
                            
                            <!-- Consumer Match Status -->
                            <div id="consumer-match-status" class="mt-2 text-sm hidden"></div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Options</h3>
                        <div class="space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    ℹ️ Note: When you assign this meter, it will automatically be marked as "Cut from Store" (removed from store inventory).
                                </p>
                            </div>

                            <div class="flex items-center mt-4">
                                <input type="checkbox" name="add_to_quick_summary" id="add_to_quick_summary" value="1" {{ old('add_to_quick_summary') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="add_to_quick_summary" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Add to Quick Summary
                                </label>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Check this to add this meter to quick summary (Extra Summary)</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.meters.index') }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition">
                            Skip for Now
                        </a>
                        <button type="submit" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Assign Meter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applicationSelect = document.getElementById('application_id');
    const consumerSelect = document.getElementById('consumer_id');
    const applicationDetails = document.getElementById('application-details');
    const consumerMatchStatus = document.getElementById('consumer-match-status');
    const meterId = {{ $meter->id }};
    
    applicationSelect.addEventListener('change', function() {
        const applicationId = this.value;
        
        // Hide details if no application selected
        if (!applicationId) {
            applicationDetails.classList.add('hidden');
            consumerMatchStatus.classList.add('hidden');
            consumerSelect.value = '';
            return;
        }
        
        // Show loading state
        applicationDetails.classList.remove('hidden');
        document.getElementById('app-no').textContent = 'Loading...';
        document.getElementById('app-name').textContent = 'Loading...';
        document.getElementById('app-cnic').textContent = 'Loading...';
        document.getElementById('app-phone').textContent = 'Loading...';
        
        // Make AJAX call
        fetch(`/admin/meters/${meterId}/assign/get-application`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                application_id: applicationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update application details
                document.getElementById('app-no').textContent = data.application.application_no;
                document.getElementById('app-name').textContent = data.application.customer_name;
                document.getElementById('app-cnic').textContent = data.application.cnic || 'N/A';
                document.getElementById('app-phone').textContent = data.application.phone || 'N/A';
                
                // Auto-select consumer if found
                if (data.consumer) {
                    consumerSelect.value = data.consumer.id;
                    consumerMatchStatus.classList.remove('hidden');
                    consumerMatchStatus.innerHTML = '<span class="text-green-600 dark:text-green-400">✓ Consumer matched and auto-selected: ' + data.consumer.name + '</span>';
                    consumerMatchStatus.classList.remove('text-red-600', 'text-yellow-600');
                    consumerMatchStatus.classList.add('text-green-600', 'dark:text-green-400');
                } else {
                    consumerSelect.value = '';
                    consumerMatchStatus.classList.remove('hidden');
                    consumerMatchStatus.innerHTML = '<span class="text-yellow-600 dark:text-yellow-400">⚠ No consumer found for this application. Please select a consumer manually.</span>';
                    consumerMatchStatus.classList.remove('text-green-600', 'text-red-600');
                    consumerMatchStatus.classList.add('text-yellow-600', 'dark:text-yellow-400');
                }
            } else {
                applicationDetails.classList.add('hidden');
                consumerMatchStatus.classList.remove('hidden');
                consumerMatchStatus.innerHTML = '<span class="text-red-600 dark:text-red-400">✗ ' + (data.message || 'Error loading application details') + '</span>';
                consumerMatchStatus.classList.remove('text-green-600', 'text-yellow-600');
                consumerMatchStatus.classList.add('text-red-600', 'dark:text-red-400');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            applicationDetails.classList.add('hidden');
            consumerMatchStatus.classList.remove('hidden');
            consumerMatchStatus.innerHTML = '<span class="text-red-600 dark:text-red-400">✗ Error loading application details</span>';
            consumerMatchStatus.classList.remove('text-green-600', 'text-yellow-600');
            consumerMatchStatus.classList.add('text-red-600', 'dark:text-red-400');
        });
    });
    
    // Trigger on page load if application is already selected
    if (applicationSelect.value) {
        applicationSelect.dispatchEvent(new Event('change'));
    }
    
    // Application Search Functionality
    const applicationSearch = document.getElementById('application_search');
    const searchApplicationBtn = document.getElementById('search-application-btn');
    
    function searchApplication() {
        const applicationNo = applicationSearch.value.trim();
        
        if (!applicationNo) {
            alert('Please enter an application number');
            return;
        }
        
        // Show loading
        searchApplicationBtn.disabled = true;
        searchApplicationBtn.textContent = 'Searching...';
        
        // Make AJAX call to search application
        fetch(`/admin/meters/${meterId}/assign/get-application`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                application_no: applicationNo
            })
        })
        .then(response => response.json())
        .then(data => {
            searchApplicationBtn.disabled = false;
            searchApplicationBtn.textContent = 'Search';
            
            if (data.success) {
                // Check if application exists in dropdown
                const optionExists = Array.from(applicationSelect.options).some(opt => opt.value == data.application.id);
                
                if (!optionExists) {
                    // Add option to dropdown
                    const option = document.createElement('option');
                    option.value = data.application.id;
                    option.textContent = data.application.application_no + ' - ' + data.application.customer_name;
                    option.setAttribute('data-cnic', data.application.cnic || '');
                    applicationSelect.appendChild(option);
                }
                
                // Select the application
                applicationSelect.value = data.application.id;
                applicationSelect.dispatchEvent(new Event('change'));
                
                // Show success message
                consumerMatchStatus.classList.remove('hidden');
                consumerMatchStatus.innerHTML = '<span class="text-green-600 dark:text-green-400">✓ Application found and selected</span>';
                consumerMatchStatus.classList.remove('text-red-600', 'text-yellow-600');
                consumerMatchStatus.classList.add('text-green-600', 'dark:text-green-400');
            } else {
                // Show error
                consumerMatchStatus.classList.remove('hidden');
                consumerMatchStatus.innerHTML = '<span class="text-red-600 dark:text-red-400">✗ ' + (data.message || 'Application not found') + '</span>';
                consumerMatchStatus.classList.remove('text-green-600', 'text-yellow-600');
                consumerMatchStatus.classList.add('text-red-600', 'dark:text-red-400');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            searchApplicationBtn.disabled = false;
            searchApplicationBtn.textContent = 'Search';
            consumerMatchStatus.classList.remove('hidden');
            consumerMatchStatus.innerHTML = '<span class="text-red-600 dark:text-red-400">✗ Error searching application</span>';
            consumerMatchStatus.classList.remove('text-green-600', 'text-yellow-600');
            consumerMatchStatus.classList.add('text-red-600', 'dark:text-red-400');
        });
    }
    
    searchApplicationBtn.addEventListener('click', searchApplication);
    applicationSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchApplication();
        }
    });
});
</script>
@endsection

