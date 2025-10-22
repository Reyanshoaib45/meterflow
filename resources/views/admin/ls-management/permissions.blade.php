@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold">Manage LS Permissions - {{ $user->name }}</h2>
                    <a href="{{ route('admin.ls-management') }}" class="text-gray-600 hover:text-gray-800">← Back</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.ls-management.update-permissions', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Application Management</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="view_applications" 
                                    {{ in_array('view_applications', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2">View Applications</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="edit_applications" 
                                    {{ in_array('edit_applications', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Edit/Update Application Status</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="create_applications" 
                                    {{ in_array('create_applications', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Create New Applications</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Global Summary Management</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="view_global_summary" 
                                    {{ in_array('view_global_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">View Global Summaries</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="create_global_summary" 
                                    {{ in_array('create_global_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Create Global Summaries</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="edit_global_summary" 
                                    {{ in_array('edit_global_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Edit Global Summaries</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Extra Summary Management</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="view_extra_summary" 
                                    {{ in_array('view_extra_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">View Extra Summaries</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="create_extra_summary" 
                                    {{ in_array('create_extra_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Create Extra Summaries</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="edit_extra_summary" 
                                    {{ in_array('edit_extra_summary', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">Edit Extra Summaries</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Meter Management</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="view_meters" 
                                    {{ in_array('view_meters', $permissions) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2">View Meter Store</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Save Permissions
                        </button>
                        <a href="{{ route('admin.ls-management') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
