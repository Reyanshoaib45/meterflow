@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">SDC Dashboard</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Welcome back, {{ Auth::user()->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Assigned Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Assigned Applications</h2>
            </div>

            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Application No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Customer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Meter Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Assigned</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($applications as $app)
                                @php
                                    $assignmentHistory = \App\Models\ApplicationHistory::where('application_id', $app->id)
                                        ->where('action_type', 'status_changed')
                                        ->where('remarks', 'like', '%assigned_sdc_id%')
                                        ->latest()
                                        ->first();
                                    $assignmentTime = $assignmentHistory ? $assignmentHistory->created_at : $app->updated_at;
                                    $hoursSinceAssignment = now()->diffInHours($assignmentTime);
                                    $canEdit = $hoursSinceAssignment <= 24;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $app->application_no }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $app->customer_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $app->meter_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($app->status == 'approved') bg-green-100 text-green-800 
                                            @elseif($app->status == 'rejected') bg-red-100 text-red-800 
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $assignmentTime->format('d M Y H:i') }}
                                        <br>
                                        <span class="text-xs {{ $canEdit ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $canEdit ? (24 - $hoursSinceAssignment) . ' hours left' : 'Expired' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col gap-2">
                                            <div>
                                                <a href="{{ route('sdc.show-global-summary', $app->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">
                                                    View Global Summary
                                                </a>
                                            </div>
                                            <div>
                                                @if($canEdit)
                                                    <a href="{{ route('sdc.edit-meter', $app->id) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 mr-3">
                                                        Edit Meter
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">Edit Expired</span>
                                                @endif
                                            </div>
                                            <div>
                                                <form action="{{ route('sdc.request-deletion', $app->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Are you sure you want to request deletion? This will contact admin.')" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400">
                                                        Request Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No applications assigned to you yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
