@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Edit Subdivision</h2>
                    <a href="{{ route('admin.subdivisions') }}" class="text-gray-600 hover:text-gray-800">
                        ← Back to Subdivisions
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

                <form method="POST" action="{{ route('admin.subdivisions.update', $subdivision) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                            <select name="company_id" id="company_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    required>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ (old('company_id', $subdivision->company_id) == $company->id) ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Subdivision Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $subdivision->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">Subdivision Code</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $subdivision->code) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                   required>
                        </div>

                        <div>
                            <label for="ls_id" class="block text-sm font-medium text-gray-700">LS User (Optional)</label>
                            <select name="ls_id" id="ls_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select an LS User</option>
                                @foreach($lsUsers as $user)
                                    <option value="{{ $user->id }}" {{ (old('ls_id', $subdivision->ls_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.subdivisions') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Subdivision
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection