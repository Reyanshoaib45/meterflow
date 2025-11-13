@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Global Summaries', 'url' => '']
        ]" />
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8" data-aos="fade-up">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Global Summaries</h3>
                <a href="{{ route('admin.global-summaries.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Add New Global Summary
                </a>
            </div>

            <div class="p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if($globalSummaries->count() > 0)
                    <div class="custom-table-container">
                        <table class="custom-table compact">
                            <thead>
                            <tr>
                                <th>Application No</th>
                                <th>Customer Name</th>
                                <th>Meter No</th>
                                <th>SIM Number</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($globalSummaries as $summary)
                                <tr>
                                    <td>{{ $summary->application_no }}</td>
                                    <td>{{ $summary->customer_name }}</td>
                                    <td>{{ $summary->meter_no ?? 'N/A' }}</td>
                                    <td>{{ $summary->sim_number ?? 'N/A' }}</td>
                                    <td class="space-x-3">
                                        <a href="{{ route('admin.global-summaries.show', $summary) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                        <a href="{{ route('admin.global-summaries.edit', $summary) }}"
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.global-summaries.destroy', $summary) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this summary?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        No global summaries found.
                    </p>
                @endif

                <div class="mt-4">
                    {{ $globalSummaries->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
