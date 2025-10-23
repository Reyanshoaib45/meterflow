@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Edit User</h2>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-gray-800">
                        ← Back to Users
                    </a>
                </div>

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

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required onchange="toggleSubdivisionField()">
                                <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : '' }}>User</option>
                                <option value="ls" {{ (old('role', $user->role) == 'ls') ? 'selected' : '' }}>Login Subdivision (LS)</option>
                                <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div id="subdivision-field" style="display: none;">
                            <label for="subdivision_ids" class="block text-sm font-medium text-gray-700">Assign Subdivisions</label>
                            <select name="subdivision_ids[]" id="subdivision_ids" multiple
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($subdivisions as $subdivision)
                                    @php
                                        $isAssigned = $user->subdivisions->contains($subdivision->id);
                                        $isOldSelected = is_array(old('subdivision_ids')) && in_array($subdivision->id, old('subdivision_ids'));
                                    @endphp
                                    <option value="{{ $subdivision->id }}" 
                                        {{ ($isOldSelected || (!old('subdivision_ids') && $isAssigned)) ? 'selected' : '' }}>
                                        {{ $subdivision->name }} ({{ $subdivision->code }}) - {{ $subdivision->company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                <i class="fas fa-info-circle text-blue-500"></i>
                                Select multiple subdivisions from the dropdown
                            </p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (Leave blank to keep current)</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 for subdivision multi-select
    $('#subdivision_ids').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Select subdivisions',
        allowClear: true,
        closeOnSelect: false
    });
    
    // Toggle function
    window.toggleSubdivisionField = function() {
        const role = $('#role').val();
        const subdivisionField = $('#subdivision-field');
        const subdivisionSelect = $('#subdivision_ids');
        
        if (role === 'ls') {
            subdivisionField.show();
            subdivisionSelect.prop('required', true);
        } else {
            subdivisionField.hide();
            subdivisionSelect.prop('required', false);
            // Clear selection using Select2 method
            subdivisionSelect.val(null).trigger('change');
        }
    };
    
    // Initialize on page load
    toggleSubdivisionField();
});
</script>
@endsection