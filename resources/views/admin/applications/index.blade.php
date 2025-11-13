@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Applications', 'url' => '']
        ]" />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8" data-aos="fade-up">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Applications Management</h3>
            </div>

            <div class="p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                @if($applications->count() > 0)
                    <div class="custom-table-container">
                        <table class="custom-table compact">
                            <thead>
                            <tr>
                                <th>Application No</th>
                                <th>Customer</th>
                                <th>Company</th>
                                <th>Subdivision</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody data-infinite-scroll data-next-page="{{ $applications->nextPageUrl() }}">
                            @foreach($applications as $application)
                                <tr>
                                    <td>{{ $application->application_no }}</td>
                                    <td>{{ $application->customer_name }}</td>
                                    <td>{{ $application->company->name ?? 'N/A' }}</td>
                                    <td>{{ $application->subdivision->name ?? 'N/A' }}</td>

                                    <td>
                                    <span class="table-badge
                                        @if($application->status == 'approved') badge-success
                                        @elseif($application->status == 'rejected') badge-danger
                                        @else badge-warning @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    </td>

                                    <td>{{ $application->created_at->format('Y-m-d') }}</td>

                                    <td class="space-x-3">
                                        <a href="{{ route('admin.applications.edit', $application) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="{{ route('admin.applications.history', $application) }}" class="text-blue-600 hover:text-blue-900">History</a>

                                        <form action="{{ route('admin.applications.destroy', $application) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this application? This action cannot be undone.')"
                                                    class="text-red-600 hover:text-red-900">
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
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No applications found.</p>
                @endif

            </div>
        </div>

    </div>
@endsection
