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

            @if(!$canEdit)
                <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 rounded-lg">
                    <p class="font-semibold">Edit time expired</p>
                    <p class="text-sm mt-1">You can only edit meter details within 24 hours of assignment. Please contact admin for changes.</p>
                </div>
            @endif

            <!-- Timer Display -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Remaining</label>
                        <div id="timer-display" class="text-2xl font-bold {{ $hoursRemaining > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                            <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @if($canEdit)
                                Editing allowed (within 24 hours window)
                            @else
                                Editing expired (24 hours window passed, but timer shows full 72 hours)
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('sdc.update-meter', $application->id) }}" method="POST" enctype="multipart/form-data" @if(!$canEdit) onsubmit="return false;" @endif>
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
                               @if(!$canEdit) disabled @endif
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @if(!$canEdit) opacity-50 cursor-not-allowed @endif">
                    </div>

                    <div>
                        <label for="seo_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SCO Number *</label>
                        <input type="text" name="seo_number" id="seo_number" 
                               value="{{ old('seo_number', $application->meter ? ($application->meter->seo_number ?? '') : '') }}" 
                               required
                               @if(!$canEdit) disabled @endif
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @if(!$canEdit) opacity-50 cursor-not-allowed @endif">
                    </div>

                    <div>
                        <label for="installed_on" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Installation *</label>
                        <input type="date" name="installed_on" id="installed_on" 
                               value="{{ old('installed_on', $application->meter && $application->meter->installed_on ? $application->meter->installed_on->format('Y-m-d') : '') }}" 
                               required
                               @if(!$canEdit) disabled @endif
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @if(!$canEdit) opacity-50 cursor-not-allowed @endif">
                    </div>

                    <div>
                        <label for="assigned_ro_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assign to RO (Revenue Officer)</label>
                        @if($roUsers->count() > 0)
                            <select name="assigned_ro_id" id="assigned_ro_id"
                                    @if(!$canEdit) disabled @endif
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition @if(!$canEdit) opacity-50 cursor-not-allowed @endif">
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

                    <div>
                        <label for="noc_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NOC File</label>
                        <input type="file" name="noc_file" id="noc_file" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                               @if(!$canEdit) disabled @endif>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload NOC document (PDF, JPG, PNG - Max 10MB)</p>
                        @if($application->globalSummary && $application->globalSummary->noc_file)
                            <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                                <a href="{{ asset('storage/' . $application->globalSummary->noc_file) }}" target="_blank" class="underline">
                                    View Current NOC File
                                </a>
                            </p>
                        @endif
                    </div>

                    <div>
                        <label for="demand_notice_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Demand Notice File</label>
                        <input type="file" name="demand_notice_file" id="demand_notice_file" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                               @if(!$canEdit) disabled @endif>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload Demand Notice document (PDF, JPG, PNG - Max 10MB)</p>
                        @if($application->globalSummary && $application->globalSummary->demand_notice_file)
                            <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                                <a href="{{ asset('storage/' . $application->globalSummary->demand_notice_file) }}" target="_blank" class="underline">
                                    View Current Demand Notice File
                                </a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    @if($canEdit)
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            Update Meter Details
                        </button>
                    @else
                        <button type="button" disabled class="bg-gray-400 text-gray-600 font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                            Edit Time Expired
                        </button>
                    @endif
                    <a href="{{ route('sdc.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Countdown timer
    let remainingSeconds = {{ $remainingSeconds }};
    const timerDisplay = document.getElementById('timer-display');
    const hoursSpan = document.getElementById('hours');
    const minutesSpan = document.getElementById('minutes');
    const secondsSpan = document.getElementById('seconds');

    function updateTimer() {
        if (remainingSeconds <= 0) {
            hoursSpan.textContent = '00';
            minutesSpan.textContent = '00';
            secondsSpan.textContent = '00';
            timerDisplay.classList.remove('text-blue-600', 'dark:text-blue-400');
            timerDisplay.classList.add('text-red-600', 'dark:text-red-400');
            return;
        }

        const hours = Math.floor(remainingSeconds / 3600);
        const minutes = Math.floor((remainingSeconds % 3600) / 60);
        const seconds = remainingSeconds % 60;

        hoursSpan.textContent = String(hours).padStart(2, '0');
        minutesSpan.textContent = String(minutes).padStart(2, '0');
        secondsSpan.textContent = String(seconds).padStart(2, '0');

        remainingSeconds--;

        if (remainingSeconds < 3600) { // Less than 1 hour remaining
            timerDisplay.classList.remove('text-blue-600', 'dark:text-blue-400');
            timerDisplay.classList.add('text-orange-600', 'dark:text-orange-400');
        }
    }

    // Update timer every second
    updateTimer();
    setInterval(updateTimer, 1000);
</script>
@endsection

