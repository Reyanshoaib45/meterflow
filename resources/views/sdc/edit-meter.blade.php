@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Update Meter Details</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Application: {{ $application->application_no }}</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sdc.update-meter', $application->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Number</label>
                        <p class="text-lg font-semibold">{{ $application->application_no }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer Name</label>
                        <p class="text-lg font-semibold">{{ $application->customer_name }}</p>
                    </div>

                    <div>
                        <label for="sim_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SIM Number *</label>
                        <input type="text" name="sim_number" id="sim_number" 
                               value="{{ old('sim_number', $application->meter ? ($application->meter->sim_number ?? '') : '') }}" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    </div>

                    <div>
                        <label for="seo_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Number *</label>
                        <input type="text" name="seo_number" id="seo_number" 
                               value="{{ old('seo_number', $application->meter ? ($application->meter->seo_number ?? '') : '') }}" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    </div>

                    <div>
                        <label for="installed_on" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Installation *</label>
                        <input type="date" name="installed_on" id="installed_on" 
                               value="{{ old('installed_on', $application->meter && $application->meter->installed_on ? $application->meter->installed_on->format('Y-m-d') : '') }}" 
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    </div>

                    <div>
                        <label for="assigned_ro_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign to RO (Revenue Officer)</label>
                        @if($roUsers->count() > 0)
                            <select name="assigned_ro_id" id="assigned_ro_id"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                                <option value="">-- Select RO --</option>
                                @foreach($roUsers as $roUser)
                                    <option value="{{ $roUser->id }}" {{ old('assigned_ro_id', $application->assigned_ro_id) == $roUser->id ? 'selected' : '' }}>
                                        {{ $roUser->name }} ({{ $roUser->username }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select a Revenue Officer to assign this application to</p>
                        @else
                            <select name="assigned_ro_id" id="assigned_ro_id" disabled
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                <option value="">No RO users available for this subdivision</option>
                            </select>
                            <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">No Revenue Officers are assigned to this subdivision. Please contact admin.</p>
                        @endif
                    </div>

                    @php
                        $assignmentHistory = \App\Models\ApplicationHistory::where('application_id', $application->id)
                            ->where('action_type', 'status_changed')
                            ->where('remarks', 'like', '%assigned_sdc_id%')
                            ->latest()
                            ->first();
                        $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $application->updated_at;
                        $hoursSinceAssignment = now()->diffInHours($assignmentTime);
                        $hoursLeft = 24 - $hoursSinceAssignment;
                    @endphp

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Remaining</label>
                        <p class="text-lg font-semibold {{ $hoursLeft > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $hoursLeft > 0 ? $hoursLeft . ' hours left' : 'Expired' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        Update Meter Details
                    </button>
                    <a href="{{ route('sdc.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

