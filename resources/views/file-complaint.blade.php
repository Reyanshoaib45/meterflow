@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['name' => 'File Complaint', 'url' => '']
        ]" />

        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="bg-gradient-to-br from-red-500 to-red-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">File a Complaint</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">Report your electricity service issue or concern</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6 alert animate-slide-in-right">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @if(session('complaint_id'))
                    <p class="mt-2 text-sm">Your Complaint ID: <span class="font-bold">{{ session('complaint_id') }}</span></p>
                @endif
            </div>
        @endif

        <!-- Complaint Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 animate-fade-in-up">
            <form action="{{ route('store-complaint') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Company Selection -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Company <span class="text-red-500">*</span>
                    </label>
                    <select name="company_id" id="company_id" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        <option value="">-- Select Company --</option>
                        @foreach(\App\Models\Company::all() as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subdivision Selection -->
                <div>
                    <label for="subdivision_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Subdivision <span class="text-red-500">*</span>
                    </label>
                    <select name="subdivision_id" id="subdivision_id" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        <option value="">-- Select Subdivision --</option>
                    </select>
                    @error('subdivision_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subdivision Message (if exists) -->
                <div id="subdivision-message" class="hidden bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 dark:border-blue-700 p-4 rounded text-gray-900 dark:text-white"></div>

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Enter your full name">
                    @error('customer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="03XX-XXXXXXX">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference Number (Required) -->
                <div>
                    <label for="consumer_ref" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="consumer_ref" id="consumer_ref" value="{{ old('consumer_ref') }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Enter your reference number">
                    @error('consumer_ref')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Complaint Type -->
                <div>
                    <label for="complaint_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Complaint Type <span class="text-red-500">*</span>
                    </label>
                    <select name="complaint_type" id="complaint_type" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        <option value="">-- Select Type --</option>
                        <option value="billing" {{ old('complaint_type') == 'billing' ? 'selected' : '' }}>Billing Issue</option>
                        <option value="power_outage" {{ old('complaint_type') == 'power_outage' ? 'selected' : '' }}>Power Outage</option>
                        <option value="meter" {{ old('complaint_type') == 'meter' ? 'selected' : '' }}>Meter Problem</option>
                        <option value="service" {{ old('complaint_type') == 'service' ? 'selected' : '' }}>Service Quality</option>
                        <option value="other" {{ old('complaint_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('complaint_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Brief description of your complaint">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Detailed Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                        placeholder="Provide detailed information about your complaint">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priority Level <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('landing') }}" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition text-center">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl transform hover:scale-105">
                        Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('company_id');
    const subdivisionSelect = document.getElementById('subdivision_id');
    const subdivisionMessage = document.getElementById('subdivision-message');
    
    companySelect.addEventListener('change', function() {
        const companyId = this.value;
        subdivisionSelect.innerHTML = '<option value="">-- Select Subdivision --</option>';
        subdivisionMessage.classList.add('hidden');
        
        if (companyId) {
            fetch(`/api/subdivisions/${companyId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subdivision => {
                        const option = document.createElement('option');
                        option.value = subdivision.id;
                        option.textContent = subdivision.name;
                        option.dataset.message = subdivision.subdivision_message;
                        subdivisionSelect.appendChild(option);
                    });
                });
        }
    });
    
    subdivisionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const message = selectedOption.dataset.message;
        
        if (message && message.trim() !== '') {
            subdivisionMessage.innerHTML = `
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-900">Important Notice:</p>
                        <p class="text-blue-800 mt-1">${message}</p>
                    </div>
                </div>
            `;
            subdivisionMessage.classList.remove('hidden');
        } else {
            subdivisionMessage.classList.add('hidden');
        }
    });
});
</script>
@endsection
