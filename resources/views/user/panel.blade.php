@extends('layouts.app')

@section('title', 'User Dashboard')

@section('canonical')
    <link rel="canonical" href="{{ url('/user/panel') }}" />
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Your personal dashboard for recent activities and applications on Meter Flow Nation." />
    <meta property="og:title" content="User Dashboard - {{ config('app.name') }}" />
    <meta property="og:description" content="View your recent activities and applications." />
    <meta property="og:url" content="{{ url('/user/panel') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('images/mfn-logo.png') }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="User Dashboard - {{ config('app.name') }}" />
    <meta name="twitter:description" content="View your recent activities and applications." />
    <meta name="twitter:image" content="{{ asset('images/mfn-logo.png') }}" />
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8" data-aos="fade-down">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Your Panel</h1>
        <p class="text-gray-600 dark:text-gray-300">Welcome, {{ $user->name }}. View your recent activities and related applications.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activities -->
        <div class="lg:col-span-2" data-aos="fade-right">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Recent Activities</h2>
                @if($activities->count())
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($activities as $act)
                            <li class="py-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors" data-aos="fade-up" data-aos-delay="100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($act->action_type ?? 'activity') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $act->remarks ?? '—' }}</p>
                                        @if($act->application)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Application: {{ $act->application->application_no }}</p>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $act->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">{{ $activities->links() }}</div>
                @else
                    <p class="text-gray-600 dark:text-gray-300">No recent activities.</p>
                @endif
            </div>
        </div>

        <!-- Applications -->
        <div data-aos="fade-left">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Your Applications</h2>
                @if($applications->count())
                    <ul class="space-y-3">
                        @foreach($applications as $app)
                            <li class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 hover:shadow-lg" data-aos="zoom-in" data-aos-delay="150">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $app->application_no }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                    Status: 
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($app->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($app->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </p>
                                @if($app->fee_amount)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Fee: <span class="font-semibold text-green-600 dark:text-green-400">Rs. {{ number_format($app->fee_amount, 2) }}</span></p>
                                @endif
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $app->company->name ?? '—' }} • {{ $app->subdivision->name ?? '—' }}</p>
                                @if($app->status == 'approved' && $app->fee_amount)
                                <a href="{{ route('application.invoice', $app->application_no) }}" 
                                   class="inline-flex items-center text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Generate Invoice
                                </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-300">No applications linked to your recent activity.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
