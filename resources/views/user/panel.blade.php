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
        <h1 class="text-3xl font-bold text-gray-900">Your Panel</h1>
        <p class="text-gray-600">Welcome, {{ $user->name }}. View your recent activities and related applications.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Activities -->
        <div class="lg:col-span-2" data-aos="fade-right">
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-4">Recent Activities</h2>
                @if($activities->count())
                    <ul class="divide-y divide-gray-200">
                        @foreach($activities as $act)
                            <li class="py-4 hover:bg-gray-50 rounded-lg transition-colors" data-aos="fade-up" data-aos-delay="100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ ucfirst($act->action_type ?? 'activity') }}</p>
                                        <p class="text-sm text-gray-600">{{ $act->remarks ?? '—' }}</p>
                                        @if($act->application)
                                            <p class="text-xs text-gray-500 mt-1">Application: {{ $act->application->application_no }}</p>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $act->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">{{ $activities->links() }}</div>
                @else
                    <p class="text-gray-600">No recent activities.</p>
                @endif
            </div>
        </div>

        <!-- Applications -->
        <div data-aos="fade-left">
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <h2 class="text-xl font-semibold mb-4">Your Applications</h2>
                @if($applications->count())
                    <ul class="space-y-3">
                        @foreach($applications as $app)
                            <li class="p-4 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-300 hover:shadow-lg" data-aos="zoom-in" data-aos-delay="150">
                                <p class="font-medium text-gray-900">{{ $app->application_no }}</p>
                                <p class="text-sm text-gray-600">Status: <span class="font-medium">{{ ucfirst($app->status) }}</span></p>
                                <p class="text-xs text-gray-500">{{ $app->company->name ?? '—' }} • {{ $app->subdivision->name ?? '—' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">No applications linked to your recent activity.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
