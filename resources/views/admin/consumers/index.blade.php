@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Consumers Management</h2>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6 bg-gray-50 p-4 rounded">
                    <form method="GET" action="{{ route('admin.consumers.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Search by name, CNIC, phone..." 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                        </div>
                        <div>
                            <select name="subdivision" class="rounded-md border-gray-300 shadow-sm">
                                <option value="">All Subdivisions</option>
                                @foreach($subdivisions as $subdivision)
                                    <option value="{{ $subdivision->id }}" {{ request('subdivision') == $subdivision->id ? 'selected' : '' }}>
                                        {{ $subdivision->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filter
                        </button>
                        <a href="{{ route('admin.consumers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Clear
                        </a>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNIC</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdivision</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Connection Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($consumers as $consumer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $consumer->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $consumer->cnic }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $consumer->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $consumer->subdivision->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $consumer->connection_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($consumer->status == 'active') bg-green-100 text-green-800 
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($consumer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.consumers.show', $consumer) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            View Details
                                        </a>
                                        <a href="{{ route('admin.consumers.applications', $consumer) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Applications
                                        </a>
                                        <a href="{{ route('admin.consumers.history', $consumer) }}" class="text-green-600 hover:text-green-900">
                                            History
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No consumers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $consumers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
