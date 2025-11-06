@extends('layouts.app')

@section('title', 'Your Profile')

@section('canonical')
    <link rel="canonical" href="{{ url('/profile') }}" />
@endsection

@section('meta')
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="Manage your account profile, password, and account settings." />
    <meta property="og:title" content="Your Profile - {{ config('app.name') }}" />
    <meta property="og:description" content="Manage your account profile, password, and account settings." />
    <meta property="og:url" content="{{ url('/profile') }}" />
    <meta property="og:type" content="website" />
@endsection

@section('content')
<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-6" data-aos="fade-down">
        <h1 class="font-semibold text-2xl text-gray-900 dark:text-white">{{ __('Profile') }}</h1>
        <p class="text-gray-600 dark:text-gray-300">Update your account information and security settings.</p>
    </div>

    <div class="space-y-6">
        <!-- User Information Card (for all users with role and subdivisions) -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-2xl" data-aos="fade-up">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('User Information') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Your account role and assigned subdivisions.') }}
                        </p>
                    </header>

                    <div class="mt-6 space-y-4">
                        <div>
                            <x-input-label :value="__('Role')" />
                            <div class="mt-1">
                                @php
                                    $roleNames = [
                                        'ls' => 'LS - Line Superintendent',
                                        'sdc' => 'SDC - Smart Data Center',
                                        'ro' => 'RO - Revenue Officer',
                                        'admin' => 'Admin - Administrator',
                                        'user' => 'User - Consumer',
                                    ];
                                    $roleColors = [
                                        'ls' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'sdc' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'ro' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        'admin' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'user' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                    ];
                                    $roleName = $roleNames[$user->role] ?? strtoupper($user->role ?? 'User');
                                    $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium {{ $roleColor }}">
                                    {{ $roleName }}
                                </span>
                            </div>
                        </div>

                        @if($subdivisions->count() > 0)
                            <div>
                                <x-input-label :value="__('Assigned Subdivisions')" />
                                <div class="mt-2 space-y-2">
                                    @foreach($subdivisions as $subdivision)
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $subdivision->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        Code: {{ $subdivision->code }}
                                                        @if($subdivision->company)
                                                            | {{ $subdivision->company->name }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(in_array($user->role, ['ls', 'sdc', 'ro']))
                            <div>
                                <x-input-label :value="__('Assigned Subdivisions')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('No subdivisions assigned.') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-2xl" data-aos="fade-up">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password - Show for all users -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account - Show for all users -->
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow border border-gray-200 dark:border-gray-700 rounded-2xl" data-aos="fade-up" data-aos-delay="150">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
