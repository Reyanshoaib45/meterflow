@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">New Meter Request</h1>
        <p class="text-xl text-gray-600 dark:text-gray-300">Application / NOC Form.</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        {{-- Left: Informational panel --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg p-8 border border-blue-100 dark:border-gray-700 animate-fade-in-left">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Apply for New Meter</h2>
            </div>
            
            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">Enter your application number to start — further fields unlock progressively.</p>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 mb-6 border border-blue-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    How it works
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <div class="bg-blue-100 rounded-full p-1 mt-1 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300">Fill application number first</span>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-1 mt-1 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300">Provide contact details</span>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-1 mt-1 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700 dark:text-gray-300">Submit and track status via application number</span>
                    </li>
                </ul>
            </div>
            
            <div class="bg-blue-500 text-white rounded-lg p-5">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">Your application number is unique and will be used to track your application status throughout the process.</p>
                </div>
            </div>
        </div>

        {{-- Right: Form card --}}
        <div x-data="applicationForm()" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700 animate-fade-in-right">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Application Form</h3>
            </div>

            {{-- server-side validation errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 animate-shake">
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <ul class="mt-2 list-disc pl-5 space-y-1 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('applications.store') }}" @submit.prevent="submit($event)" novalidate>
                @csrf

                {{-- Application Number (first field) --}}
                <div class="mb-6">
                    <label for="application_no_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Number <span class="text-red-500">*</span></label>
                    <input x-model="form.application_no" @input="checkUnlock" @keydown.enter="focusNext('customer_name_field')" name="application_no"
                        value="{{ old('application_no') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Enter unique application number (e.g., APP-2024-001)" required id="application_no_field"
                        pattern="[A-Za-z0-9-]+" title="Alphanumeric characters and hyphen only">
                    <p x-show="applicationChecking" class="text-xs text-blue-600 mt-2 flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-1" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Checking availability...
                    </p>
                    <p x-show="!applicationChecking && applicationAvailable && form.application_no" class="text-xs text-green-600 mt-2 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-text="applicationMessage"></span>
                    </p>
                    <p x-show="!applicationChecking && !applicationAvailable && form.application_no" class="text-xs text-red-600 mt-2 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-text="applicationMessage"></span>
                    </p>
                    <p x-show="!unlocked && !form.application_no" class="text-xs text-gray-500 mt-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Enter unique application number to unlock rest of the form.
                    </p>
                </div>

                {{-- Progressive unlocked fields --}}
                <div x-show="unlocked" x-transition class="space-y-6">
                    <!-- Customer Name -->
                    <div x-show="step >= 1" x-transition>
                        <label for="customer_name_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Name <span class="text-red-500">*</span></label>
                        <input x-model="form.customer_name" @input="checkStep(1)" name="customer_name" value="{{ old('customer_name') }}" :disabled="step < 1"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            placeholder="Enter your full name" id="customer_name_field" required>
                    </div>

                    <!-- CNIC -->
                    <div x-show="step >= 2" x-transition>
                        <label for="customer_cnic_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CNIC <span class="text-red-500">*</span></label>
                        <input x-model="form.customer_cnic" @input="validateCnic(); checkStep(2)" name="customer_cnic" value="{{ old('customer_cnic') }}" :disabled="step < 2"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            placeholder="3520212345671 (13 digits)" id="customer_cnic_field" required
                            pattern="[0-9]{13}" maxlength="13" title="CNIC must be 13 digits">
                        <p x-show="cnicError" class="text-xs text-red-600 mt-1">
                            <i class="fas fa-exclamation-circle"></i> CNIC must be exactly 13 numeric digits
                        </p>
                    </div>

                    <!-- Phone -->
                    <div x-show="step >= 3" x-transition>
                        <label for="phone_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone <span class="text-red-500">*</span></label>
                        <input x-model="form.phone" @input="validatePhone(); checkStep(3)" name="phone" value="{{ old('phone') }}" :disabled="step < 3"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            placeholder="923001234567 (12 digits)" id="phone_field" required
                            pattern="[0-9]{12}" maxlength="12" title="Phone must be 12 digits">
                        <p x-show="phoneError" class="text-xs text-red-600 mt-1">
                            <i class="fas fa-exclamation-circle"></i> Phone must be exactly 12 numeric digits
                        </p>
                        <p x-show="!phoneError && form.phone && form.phone.length === 12" class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle"></i> Valid phone number
                        </p>
                    </div>

                    <!-- Address -->
                    <div x-show="step >= 4" x-transition>
                        <label for="address_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address <span class="text-red-500">*</span></label>
                        <textarea x-model="form.address" @input="checkStep(4)" name="address" :disabled="step < 4"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800"
                            rows="3" placeholder="Enter your full address" id="address_field" required>{{ old('address') }}</textarea>
                    </div>

                    <!-- Company -->
                    <div x-show="step >= 5" x-transition>
                        <label for="company_id_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company <span class="text-red-500">*</span></label>
                        <select x-model="form.company_id" @change="checkStep(5)" name="company_id" :disabled="step < 5"
                            class="no-select2 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            id="company_id_field" required>
                            <option value="">Select Company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subdivision -->
                    <div x-show="step >= 6" x-transition>
                        <label for="subdivision_id_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subdivision <span class="text-red-500">*</span></label>
                        <select x-model="form.subdivision_id" @change="checkStep(6)" name="subdivision_id" :disabled="step < 6"
                            class="no-select2 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            id="subdivision_id_field" required>
                            <option value="">Select Subdivision</option>
                            @foreach ($subdivisions ?? [] as $sd)
                                <option value="{{ $sd->id }}" @selected(old('subdivision_id') == $sd->id)>{{ $sd->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Meter Number (Optional with AJAX validation) -->
                    <div x-show="step >= 6" x-transition>
                        <label for="meter_number_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meter Number (optional)</label>
                        <input x-model="form.meter_number" @input="checkMeterNumber" name="meter_number" value="{{ old('meter_number') }}" :disabled="step < 6"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            placeholder="Enter meter number (e.g., MTR-12345)" id="meter_number_field"
                            pattern="[A-Za-z0-9-]+" title="Alphanumeric characters and hyphen only">
                        <p x-show="meterChecking" class="text-xs text-blue-600 mt-2 flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-1" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Checking meter number...
                        </p>
                        <p x-show="meterAvailable && !meterChecking && form.meter_number" class="text-xs text-green-600 mt-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span x-text="meterMessage"></span>
                        </p>
                        <p x-show="meterError && !meterChecking && form.meter_number" class="text-xs text-red-600 mt-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span x-text="meterMessage"></span>
                        </p>
                    </div>

                    <!-- Connection Type -->
                    <div x-show="step >= 6" x-transition>
                        <label for="connection_type_field" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Connection Type <span class="text-red-500">*</span></label>
                        <select x-model="form.connection_type" @change="checkStep(8)" name="connection_type" :disabled="step < 6"
                            class="no-select2 w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800" 
                            id="connection_type_field" required>
                            <option value="">Select type</option>
                            <option value="Domestic" @selected(old('connection_type') == 'Domestic')>Domestic</option>
                            <option value="Commercial" @selected(old('connection_type') == 'Commercial')>Commercial</option>
                            <option value="Industrial" @selected(old('connection_type') == 'Industrial')>Industrial</option>
                        </select>
                    </div>

                    <div x-show="step >= 6" x-transition class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <button type="button" @click="resetForm" class="w-full sm:w-auto px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Reset Form
                        </button>
                        <button type="submit" :disabled="submitting || meterError || !canSubmit" id="submit_button"
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <template x-if="!submitting">
                                <span class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Submit Application
                                </span>
                            </template>
                            <template x-if="submitting">
                                <span class="flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" fill="none"></circle>
                                    </svg>
                                    Submitting...
                                </span>
                            </template>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- small Alpine component script --}}
<script>
    function applicationForm() {
        return {
            form: {
                application_no: '',
                customer_name: '',
                customer_cnic: '',
                phone: '',
                address: '',
                company_id: '',
                subdivision_id: '',
                meter_number: '',
                connection_type: ''
            },
            unlocked: false,
            step: 0,
            submitting: false,
            meterChecking: false,
            meterAvailable: false,
            meterError: false,
            meterMessage: '',
            meterCheckTimeout: null,
            applicationChecking: false,
            applicationAvailable: false,
            applicationMessage: '',
            applicationCheckTimeout: null,
            cnicError: false,
            phoneError: false,
            canSubmit: false,

            checkUnlock() {
                const appNo = this.form.application_no && this.form.application_no.trim();
                if (appNo && appNo.length >= 3) {
                    this.checkApplicationNumber();
                } else {
                    this.unlocked = false;
                    this.applicationAvailable = false;
                }
            },

            checkApplicationNumber() {
                clearTimeout(this.applicationCheckTimeout);
                this.applicationChecking = true;
                
                this.applicationCheckTimeout = setTimeout(() => {
                    fetch('{{ route("check.application") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            application_no: this.form.application_no
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.applicationChecking = false;
                        this.applicationAvailable = data.available;
                        this.applicationMessage = data.message;
                        
                        if (data.available) {
                            this.unlocked = true;
                            if (this.step === 0) {
                                this.step = 1;
                            }
                        } else {
                            this.unlocked = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error checking application number:', error);
                        this.applicationChecking = false;
                    });
                }, 500);
            },

            validateCnic() {
                const cnic = this.form.customer_cnic;
                // Check if CNIC is exactly 13 digits
                if (cnic && cnic.length > 0) {
                    const isValid = /^[0-9]{13}$/.test(cnic);
                    this.cnicError = !isValid;
                } else {
                    this.cnicError = false;
                }
            },

            validatePhone() {
                // Remove any non-numeric characters
                this.form.phone = this.form.phone.replace(/[^0-9]/g, '');
                const phone = this.form.phone;
                // Check if phone is exactly 12 digits
                if (phone && phone.length > 0) {
                    const isValid = /^[0-9]{12}$/.test(phone);
                    this.phoneError = !isValid;
                } else {
                    this.phoneError = false;
                }
            },

            checkStep(currentStep) {
                let value = '';
                let isValid = true;
                
                switch(currentStep) {
                    case 1: value = this.form.customer_name; break;
                    case 2: 
                        value = this.form.customer_cnic;
                        // Check CNIC is valid (13 digits)
                        isValid = /^[0-9]{13}$/.test(value);
                        if (!isValid) this.cnicError = true;
                        break;
                    case 3: 
                        value = this.form.phone;
                        // Check phone is valid (12 digits)
                        isValid = /^[0-9]{12}$/.test(value);
                        if (!isValid) this.phoneError = true;
                        break;
                    case 4: value = this.form.address; break;
                    case 5: 
                        value = this.form.company_id;
                        break;
                    case 6: 
                        value = this.form.subdivision_id;
                        // Don't auto-advance past 6, all remaining fields show at step 6
                        break;
                    case 8: 
                        value = this.form.connection_type; 
                        break;
                }
                
                // Update step if value is valid and filled
                // Advance steps up to 6, then stop (all remaining fields show at step 6)
                if (value && value.toString().trim().length > 0 && isValid) {
                    if (this.step === currentStep && currentStep < 6) {
                        this.step = currentStep + 1;
                        console.log(`Step advanced from ${currentStep} to ${this.step}`);
                    }
                    // Don't advance past step 6, all remaining fields are visible
                } else {
                    console.log(`Step ${currentStep} not advanced. Value: "${value}", Valid: ${isValid}`);
                }
                
                this.updateCanSubmit();
            },

            checkMeterNumber() {
                clearTimeout(this.meterCheckTimeout);
                this.meterError = false;
                
                if (!this.form.meter_number || this.form.meter_number.trim().length === 0) {
                    this.meterChecking = false;
                    this.meterAvailable = false;
                    this.updateCanSubmit();
                    return;
                }
                
                this.meterChecking = true;
                
                this.meterCheckTimeout = setTimeout(() => {
                    fetch('{{ route("check.meter") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            meter_no: this.form.meter_number
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.meterChecking = false;
                        this.meterAvailable = data.available;
                        this.meterMessage = data.message;
                        this.meterError = !data.available;
                        this.updateCanSubmit();
                    })
                    .catch(error => {
                        console.error('Error checking meter number:', error);
                        this.meterChecking = false;
                        this.updateCanSubmit();
                    });
                }, 500);
            },

            updateCanSubmit() {
                this.canSubmit = this.form.application_no &&
                                this.applicationAvailable &&
                                !this.applicationChecking &&
                                this.form.customer_name &&
                                this.form.customer_cnic &&
                                !this.cnicError &&
                                /^[0-9]{13}$/.test(this.form.customer_cnic) &&
                                this.form.phone &&
                                !this.phoneError &&
                                /^[0-9]{12}$/.test(this.form.phone) &&
                                this.form.address &&
                                this.form.company_id &&
                                this.form.subdivision_id &&
                                this.form.connection_type &&
                                !this.meterChecking &&
                                (this.meterAvailable || !this.form.meter_number);
            },

            submit(e) {
                if (!this.canSubmit || !this.applicationAvailable || this.meterError || this.cnicError || this.phoneError) {
                    alert('Please fill all fields correctly before submitting.');
                    return false;
                }
                
                this.submitting = true;
                e.target.submit();
            },

            resetForm() {
                this.form = {
                    application_no: '',
                    customer_name: '',
                    customer_cnic: '',
                    phone: '',
                    address: '',
                    company_id: '',
                    subdivision_id: '',
                    meter_number: '',
                    connection_type: ''
                };
                this.unlocked = false;
                this.step = 0;
                this.meterError = false;
                this.meterChecking = false;
                this.cnicError = false;
                this.applicationAvailable = false;
                this.canSubmit = false;
                document.getElementById('application_no_field').focus();
            },

            init() {
                this.checkUnlock();
            }
        }
    }
</script>

<style>
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .animate-fade-in-left {
        animation: fadeInLeft 0.6s ease-out forwards;
    }
    
    .animate-fade-in-right {
        animation: fadeInRight 0.6s ease-out forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
</style>
@endsection