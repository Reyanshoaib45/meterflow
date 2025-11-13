@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Consumers', 'url' => '']
        ]" />

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8" data-aos="fade-up">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Consumers Management</h3>
            </div>

            <div class="p-6">

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded">
                    <form method="GET" action="{{ route('admin.consumers.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by name, CNIC, phone..."
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                        </div>

                        <div>
                            <select name="subdivision"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm">
                                <option value="">All Subdivisions</option>
                                @foreach($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}"
                                        {{ request('subdivision') == $subdivision->id ? 'selected' : '' }}>
                                        {{ $subdivision->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                            Filter
                        </button>

                        <a href="{{ route('admin.consumers.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                            Clear
                        </a>
                    </form>
                </div>

                @if($consumers->count() > 0)
                    <div class="custom-table-container">
                        <table class="custom-table compact">
                            <thead>
                            <tr>
                                <th>Consumer Name</th>
                                <th>CNIC</th>
                                <th>Phone</th>
                                <th>Subdivision</th>
                                <th>Connection Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody data-infinite-scroll data-next-page="{{ $consumers->nextPageUrl() }}">
                            @foreach($consumers as $consumer)
                                <tr>
                                    <td>{{ $consumer->name }}</td>
                                    <td>{{ $consumer->cnic }}</td>
                                    <td>{{ $consumer->phone }}</td>
                                    <td>{{ $consumer->subdivision->name ?? 'N/A' }}</td>
                                    <td>{{ $consumer->connection_type }}</td>

                                    <td>
                                    <span class="table-badge
                                        @if($consumer->status == 'active') badge-success
                                        @else badge-danger @endif">
                                        {{ ucfirst($consumer->status) }}
                                    </span>
                                    </td>

                                    <td class="space-x-3">
                                        <a href="{{ route('admin.consumers.show', $consumer) }}" class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                        <a href="{{ route('admin.consumers.applications', $consumer) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Applications
                                        </a>
                                        <a href="{{ route('admin.consumers.history', $consumer) }}" class="text-green-600 hover:text-green-900">
                                            History
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No consumers found.</p>
                @endif
            </div>
        </div>

    </div>
@endsection
