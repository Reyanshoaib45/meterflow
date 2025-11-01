@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Add Application History</h2>
                    <a href="{{ route('ls.application-history', $application->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        ← Back to History
                    </a>
                </div>

                <!-- Application Info -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Application No</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $application->application_no }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Customer Name</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $application->customer_name }}</div>
                        </div>
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

                <form method="POST" action="{{ route('ls.store-application-history', $application->id) }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="action_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action Type *</label>
                            <select name="action_type" id="action_type" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select Action Type</option>
                                <option value="submitted" {{ old('action_type') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ old('action_type') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('action_type') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="status_changed" {{ old('action_type') == 'status_changed' ? 'selected' : '' }}>Status Changed</option>
                                <option value="meter_assigned" {{ old('action_type') == 'meter_assigned' ? 'selected' : '' }}>Meter Assigned</option>
                                <option value="billing_created" {{ old('action_type') == 'billing_created' ? 'selected' : '' }}>Billing Created</option>
                            </select>
                        </div>

                        <div>
                            <label for="seo_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Number</label>
                            <input type="text" name="seo_number" id="seo_number" value="{{ old('seo_number') }}"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter SEO number">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional: Add SEO number if sending to RO</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks *</label>
                            <textarea name="remarks" id="remarks" rows="3" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Enter remarks...">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="sent_to_ro" id="sent_to_ro" value="1" {{ old('sent_to_ro') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="sent_to_ro" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Send to RO (Revenue Officer)
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Check this if you want to send this history to RO for billing management</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6">
                        <a href="{{ route('ls.application-history', $application->id) }}" class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition">
                            Create History
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

