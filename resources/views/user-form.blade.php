@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        {{-- Left: Informational panel --}}
        <div class="bg-white rounded-xl shadow p-6 fade-in">
            <h2 class="text-2xl font-bold mb-3">Apply for New Connection</h2>
            <p class="text-gray-600 mb-4">Enter your application number to start — further fields unlock progressively. You
                don't need to login.</p>

            <ul class="space-y-2 text-sm">
                <li class="flex items-start gap-2"><span class="text-blue-600 font-semibold">•</span> Fill application number
                    first</li>
                <li class="flex items-start gap-2"><span class="text-blue-600 font-semibold">•</span> Provide contact details
                </li>
                <li class="flex items-start gap-2"><span class="text-blue-600 font-semibold">•</span> Submit and track status
                    via application number</li>
            </ul>
        </div>

        {{-- Right: Form card --}}
        <div x-data="applicationForm()" class="bg-white rounded-xl shadow p-6 fade-in">
            <h3 class="text-xl font-semibold mb-4">Application Form</h3>

            {{-- server-side validation errors --}}
            @if ($errors->any())
                <div class="mb-4 rounded p-3 bg-red-50 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('applications.store') }}" @submit.prevent="submit($event)" novalidate>
                @csrf

                {{-- Application Number (first field) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Application Number</label>
                    <input x-model="form.application_no" @input="checkUnlock" name="application_no"
                        value="{{ old('application_no') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter application number" required>
                    <p x-show="!unlocked" class="text-xs text-gray-400 mt-1">Enter application number to unlock rest of the
                        form.</p>
                </div>

                {{-- Progressive unlocked fields --}}
                <div x-show="unlocked" x-transition class="space-y-4 mt-3">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                        <input x-model="form.customer_name" name="customer_name" value="{{ old('customer_name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">CNIC</label>
                        <input x-model="form.customer_cnic" name="customer_cnic" value="{{ old('customer_cnic') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="xxxxx-xxxxxxx-x">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input x-model="form.phone" name="phone" value="{{ old('phone') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="+92XXXXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea x-model="form.address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            rows="3">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company</label>
                            <select x-model="form.company_id" name="company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select Company</option>
                                @foreach ($companies ?? [] as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subdivision</label>
                            <select x-model="form.subdivision_id" name="subdivision_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select Subdivision</option>
                                @foreach ($subdivisions ?? [] as $sd)
                                    <option value="{{ $sd->id }}" @selected(old('subdivision_id') == $sd->id)>{{ $sd->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meter Number (optional)</label>
                            <input x-model="form.meter_number" name="meter_number" value="{{ old('meter_number') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Connection Type</label>
                            <select x-model="form.connection_type" name="connection_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select type</option>
                                <option value="Domestic" @selected(old('connection_type') == 'Domestic')>Domestic</option>
                                <option value="Commercial" @selected(old('connection_type') == 'Commercial')>Commercial</option>
                                <option value="Industrial" @selected(old('connection_type') == 'Industrial')>Industrial</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" :disabled="submitting"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <template x-if="!submitting">Submit Application</template>
                            <template x-if="submitting">
                                <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3"
                                        fill="none"></circle>
                                </svg>
                                Submitting...
                            </template>
                        </button>

                        <button type="button" @click="resetForm" class="px-4 py-2 border rounded">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- small Alpine component script --}}
    <script>
        function applicationForm() {
            return {
                form: {
                    application_no: '{{ old('application_no') }}' || '',
                    customer_name: '{{ addslashes(old('customer_name', '')) }}',
                    customer_cnic: '{{ old('customer_cnic') }}' || '',
                    phone: '{{ old('phone') }}' || '',
                    address: `{{ addslashes(old('address', '')) }}`,
                    company_id: '{{ old('company_id') }}' || '',
                    subdivision_id: '{{ old('subdivision_id') }}' || '',
                    meter_number: '{{ old('meter_number') }}' || '',
                    connection_type: '{{ old('connection_type') }}' || ''
                },
                unlocked: false,
                submitting: false,

                checkUnlock() {
                    // unlock when application_no has some characters (you can add regex rules)
                    this.unlocked = (this.form.application_no && this.form.application_no.trim().length >= 3);
                },

                submit(e) {
                    // HTML form element will be submitted via standard POST if you prefer server validation.
                    // Here we allow JS to submit to keep state for UX.
                    this.submitting = true;
                    // create a native form and submit (keeps server behavior)
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('applications.store') }}';
                    // CSRF
                    let tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = '{{ csrf_token() }}';
                    form.appendChild(tokenInput);
                    // append fields
                    for (const key in this.form) {
                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = this.form[key] ?? '';
                        form.appendChild(input);
                    }
                    document.body.appendChild(form);
                    form.submit();
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
                },

                init() {
                    this.checkUnlock();
                }
            }
        }
    </script>
@endsection
