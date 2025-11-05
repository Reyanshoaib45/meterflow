@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New User</h2>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        ← Back to Users
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg animate-slide-in-right">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">There were some errors:</div>
                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span id="email-optional" style="display: none;" class="text-gray-500">(Optional for SDC/RO)</span></label>
                            <input type="text" name="email" id="email" value="{{ old('email') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror" 
                                   placeholder="example@domain.com"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required onchange="toggleSubdivisionField()">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="ls" {{ old('role') == 'ls' ? 'selected' : '' }}>Line Superintendent (LS)</option>
                                <option value="sdc" {{ old('role') == 'sdc' ? 'selected' : '' }}>Smart Data Center (SDC)</option>
                                <option value="ro" {{ old('role') == 'ro' ? 'selected' : '' }}>Revenue Officer (RO)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div id="subdivision-field" style="display: {{ (in_array(old('role'), ['ls', 'sdc', 'ro'])) ? 'block' : 'none' }};" class="subdivision-field-container">
                            <label for="subdivision_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Subdivisions <span class="text-red-500">*</span></label>
                            <select name="subdivision_ids[]" id="subdivision_ids" multiple
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('subdivision_ids') border-red-500 @enderror">
                                @foreach($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}" 
                                        {{ (is_array(old('subdivision_ids')) && in_array($subdivision->id, old('subdivision_ids'))) ? 'selected' : '' }}>
                                        {{ $subdivision->name }} ({{ $subdivision->code }}) - {{ $subdivision->company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subdivision_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                Select multiple subdivisions from the dropdown
                            </p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-2 rounded-md hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle function - define it globally so it can be called from onchange
window.toggleSubdivisionField = function() {
    const role = $('#role').val();
    const subdivisionField = $('#subdivision-field');
    const subdivisionSelect = $('#subdivision_ids');
    const emailField = $('#email');
    const emailOptional = $('#email-optional');

    // Show subdivision field for LS, SDC, and RO roles
    if (['ls', 'sdc', 'ro'].includes(role)) {
        subdivisionField.css('display', 'block');
        subdivisionSelect.prop('required', true);
    } else {
        subdivisionField.css('display', 'none');
        subdivisionSelect.prop('required', false);
        // Clear selection using Select2 method
        subdivisionSelect.val(null).trigger('change');
    }

    // Make email optional for SDC/RO
    if (['sdc', 'ro'].includes(role)) {
        emailField.prop('required', false);
        emailOptional.css('display', 'inline');
    } else {
        emailField.prop('required', true);
        emailOptional.css('display', 'none');
    }
};

$(document).ready(function() {
    // Check if success message is present, then reset form
    @if(session('success'))
        // Reset form when success message is shown
        setTimeout(function() {
            document.querySelector('form').reset();
            // Reset Select2 dropdowns
            $('#role').val(null).trigger('change');
            $('#subdivision_ids').val(null).trigger('change');
            // Hide subdivision field
            $('#subdivision-field').css('display', 'none');
            $('#subdivision_ids').prop('required', false);
            // Hide email optional text
            $('#email-optional').css('display', 'none');
            // Remove error borders
            $('.border-red-500').removeClass('border-red-500');
        }, 500);
    @endif
    
    // First, set the old role value if it exists (only if no success message)
    @if(!session('success'))
        const oldRole = '{{ old('role', '') }}';
        if (oldRole) {
            $('#role').val(oldRole);
        }
    @endif
    
    // Initialize Select2 for subdivision multi-select with enhanced styling
    $('#subdivision_ids').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Select subdivisions (you can select multiple)',
        allowClear: true,
        closeOnSelect: false,
        language: {
            noResults: function() {
                return "No subdivisions found";
            },
            searching: function() {
                return "Searching...";
            }
        }
    }).on('select2:open', function() {
        $('.select2-dropdown').css('z-index', 9999);
    });
    
    // Immediately check and show/hide subdivision field based on current role
    function checkAndToggleSubdivision() {
        const currentRole = $('#role').val();
        if (currentRole && ['ls', 'sdc', 'ro'].includes(currentRole)) {
            $('#subdivision-field').css('display', 'block');
            $('#subdivision_ids').prop('required', true);
        }
        toggleSubdivisionField();
    }
    
    // Only run if no success message (to preserve old values on validation errors)
    @if(!session('success'))
        // Run immediately
        checkAndToggleSubdivision();
        
        // Run again after Select2 initialization
        setTimeout(function() {
            checkAndToggleSubdivision();
        }, 100);
    @endif
});
</script>
@endsection