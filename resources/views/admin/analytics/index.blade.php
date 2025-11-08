@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-breadcrumbs :items="[
            ['name' => 'Admin', 'url' => route('admin.dashboard')],
            ['name' => 'Analytics', 'url' => '']
        ]" />
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Analytics Dashboard</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg text-white">
                        <h3 class="text-sm font-medium opacity-90">Total Applications</h3>
                        <p class="text-4xl font-bold mt-2">{{ \App\Models\Application::count() }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-lg text-white">
                        <h3 class="text-sm font-medium opacity-90">Active Meters</h3>
                        <p class="text-4xl font-bold mt-2">{{ \App\Models\Meter::where('status', 'active')->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-lg text-white">
                        <h3 class="text-sm font-medium opacity-90">Total Consumers</h3>
                        <p class="text-4xl font-bold mt-2">{{ \App\Models\Consumer::count() }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Application Status</h3>
                        <div class="space-y-3">
                            @php
                                $statuses = \App\Models\Application::select('status', \DB::raw('count(*) as count'))
                                    ->groupBy('status')
                                    ->get();
                            @endphp
                            @foreach($statuses as $status)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">{{ ucfirst($status->status) }}</span>
                                    <span class="font-bold text-gray-900">{{ $status->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Billing Overview</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Bills</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Bill::count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Paid Bills</span>
                                <span class="font-bold text-green-600">{{ \App\Models\Bill::where('payment_status', 'paid')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Overdue Bills</span>
                                <span class="font-bold text-red-600">{{ \App\Models\Bill::where('payment_status', 'overdue')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                    <div class="border rounded-lg divide-y">
                        @php
                            $recentLogs = \App\Models\AuditLog::with('user')->latest()->take(10)->get();
                        @endphp
                        @forelse($recentLogs as $log)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium">{{ $log->user->name ?? 'System' }}</span>
                                        <span class="text-gray-600">{{ $log->action }} {{ $log->module }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">No recent activity</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
