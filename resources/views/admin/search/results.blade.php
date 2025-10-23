@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">
                        Search Results for "{{ $query }}"
                    </h2>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                        ← Back to Dashboard
                    </a>
                </div>

                <!-- Search Form -->
                <div class="mb-8">
                    <form method="GET" action="{{ route('admin.search') }}" class="flex gap-2">
                        <input type="text" name="q" value="{{ $query }}" 
                               class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Search consumers, meters, bills, complaints..." autofocus>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </form>
                </div>

                <!-- Results Summary -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $results['consumers']->count() }}</div>
                        <div class="text-sm text-gray-600">Consumers</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $results['meters']->count() }}</div>
                        <div class="text-sm text-gray-600">Meters</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $results['bills']->count() }}</div>
                        <div class="text-sm text-gray-600">Bills</div>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600">{{ $results['complaints']->count() }}</div>
                        <div class="text-sm text-gray-600">Complaints</div>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $results['subdivisions']->count() }}</div>
                        <div class="text-sm text-gray-600">Subdivisions</div>
                    </div>
                </div>

                <!-- Consumers Results -->
                @if($results['consumers']->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Consumers ({{ $results['consumers']->count() }})
                    </h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNIC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdivision</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results['consumers'] as $consumer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $consumer->consumer_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $consumer->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $consumer->cnic }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $consumer->subdivision->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.consumers.show', $consumer) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Meters Results -->
                @if($results['meters']->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-tachometer-alt text-green-600 mr-2"></i>
                        Meters ({{ $results['meters']->count() }})
                    </h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meter No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdivision</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results['meters'] as $meter)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $meter->meter_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $meter->consumer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $meter->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($meter->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $meter->subdivision->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.meters.show', $meter) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Bills Results -->
                @if($results['bills']->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-file-invoice-dollar text-purple-600 mr-2"></i>
                        Bills ({{ $results['bills']->count() }})
                    </h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results['bills'] as $bill)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bill->bill_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bill->consumer->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rs. {{ number_format($bill->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $bill->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($bill->payment_status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($bill->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.bills.show', $bill) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Complaints Results -->
                @if($results['complaints']->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-exclamation-circle text-orange-600 mr-2"></i>
                        Complaints ({{ $results['complaints']->count() }})
                    </h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Complaint ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results['complaints'] as $complaint)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $complaint->complaint_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($complaint->subject, 40) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $complaint->consumer->name ?? $complaint->customer_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $complaint->status === 'resolved' ? 'bg-green-100 text-green-800' : 
                                               ($complaint->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Subdivisions Results -->
                @if($results['subdivisions']->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 flex items-center">
                        <i class="fas fa-building text-indigo-600 mr-2"></i>
                        Subdivisions ({{ $results['subdivisions']->count() }})
                    </h3>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results['subdivisions'] as $subdivision)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subdivision->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subdivision->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $subdivision->company->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $subdivision->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $subdivision->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.subdivisions.edit', $subdivision) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- No Results -->
                @if($results['consumers']->count() === 0 && 
                    $results['meters']->count() === 0 && 
                    $results['bills']->count() === 0 && 
                    $results['complaints']->count() === 0 && 
                    $results['subdivisions']->count() === 0)
                <div class="text-center py-12">
                    <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Results Found</h3>
                    <p class="text-gray-500">Try searching with different keywords</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
