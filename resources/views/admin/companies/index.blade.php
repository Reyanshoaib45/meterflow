@extends('layouts.app')

@section('content')
<div class="py-12 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Companies', 'url' => '']
        ]" />
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-white">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Companies Management</h2>
                    <a href="{{ route('admin.companies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Company
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 text-red-600">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="custom-table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Subdivisions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->code }}</td>
                                    <td>{{ $company->address ?? 'N/A' }}</td>
                                    <td>{{ $company->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="table-badge badge-info">{{ $company->subdivisions->count() }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="table-action-btn btn-edit">Edit</a>
                                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this company?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-empty-state">
                                        <div class="table-empty-icon">🏢</div>
                                        No companies found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection