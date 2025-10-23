@extends('layouts.app')

@section('content')
<div class="py-12 page-transition">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in-up">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Edit Subdivision Message</h2>
                    <p class="text-gray-600 mt-1">Set a custom message for: <span class="font-semibold">{{ $subdivision->name }}</span></p>
                    <p class="text-sm text-gray-500 mt-2">This message will be displayed to users when they select this subdivision while filing a complaint.</p>
                </div>

                <form action="{{ route('admin.subdivisions.update-message', $subdivision) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Subdivision Message -->
                    <div>
                        <label for="subdivision_message" class="block text-sm font-medium text-gray-700 mb-2">
                            Custom Message
                        </label>
                        <textarea name="subdivision_message" id="subdivision_message" rows="5"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter a message for users filing complaints about this subdivision (optional)">{{ old('subdivision_message', $subdivision->subdivision_message) }}</textarea>
                        @error('subdivision_message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Maximum 500 characters</p>
                    </div>

                    <!-- Preview -->
                    @if($subdivision->subdivision_message)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold text-blue-900">Current Message Preview:</p>
                                    <p class="text-blue-800 mt-1">{{ $subdivision->subdivision_message }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded text-center">
                            <p class="text-gray-500 text-sm">No message set. Users won't see any special notice for this subdivision.</p>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.subdivisions') }}" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition text-center">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl">
                            Update Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
