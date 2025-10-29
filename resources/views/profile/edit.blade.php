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
        <h1 class="font-semibold text-2xl text-gray-900">{{ __('Profile') }}</h1>
        <p class="text-gray-600">Update your account information and security settings.</p>
    </div>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow border border-gray-200 rounded-2xl" data-aos="fade-up">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow border border-gray-200 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow border border-gray-200 rounded-2xl" data-aos="fade-up" data-aos-delay="150">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
