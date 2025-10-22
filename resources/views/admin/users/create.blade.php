@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Create New User</h2>
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

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required onchange="toggleSubdivisionField()">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="ls" {{ old('role') == 'ls' ? 'selected' : '' }}>Login Subdivision (LS)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div id="subdivision-field" style="display: {{ old('role') == 'ls' ? 'block' : 'none' }};">
                            <label for="subdivision_id" class="block text-sm font-medium text-gray-700">Subdivision</label>
                            <select name="subdivision_id" id="subdivision_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Subdivision</option>
                                @foreach($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}" {{ old('subdivision_id') == $subdivision->id ? 'selected' : '' }}>
                                        {{ $subdivision->name }} ({{ $subdivision->code }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Select the subdivision this LS user will manage</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
function toggleSubdivisionField() {
    const role = document.getElementById('role').value;
    const subdivisionField = document.getElementById('subdivision-field');
    const subdivisionSelect = document.getElementById('subdivision_id');
    
    if (role === 'ls') {
        subdivisionField.style.display = 'block';
        subdivisionSelect.required = true;
    } else {
        subdivisionField.style.display = 'none';
        subdivisionSelect.required = false;
        subdivisionSelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', toggleSubdivisionField);
</script>
@endsection