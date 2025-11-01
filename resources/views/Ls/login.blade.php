@extends('layouts.app')

@php
    $routeName = request()->route()->getName();
    $roleType = 'LS';
    $authRoute = 'ls.authenticate';
    $selectRoute = 'ls.select-subdivision';
    $title = 'LS Login';
    
    if (str_contains($routeName, 'sdc')) {
        $roleType = 'SDC';
        $authRoute = 'sdc.authenticate';
        $selectRoute = 'sdc.select-subdivision';
        $title = 'SDC Login';
    } elseif (str_contains($routeName, 'ro')) {
        $roleType = 'RO';
        $authRoute = 'ro.authenticate';
        $selectRoute = 'ro.select-subdivision';
        $title = 'RO Login';
    }
@endphp

@section('title', $title . ' - ' . $subdivision->name)

@section('canonical')
    <link rel="canonical" href="{{ url(request()->path()) }}" />
@endsection

@section('meta')
    <meta name="description" content="Secure login portal for {{ $roleType }} access to {{ $subdivision->name }} subdivision management dashboard." />
    <meta name="robots" content="noindex, nofollow" />
    <meta property="og:title" content="{{ $title }} - {{ $subdivision->name }}" />
    <meta property="og:description" content="Authorized access only for {{ $roleType }}." />
    <meta property="og:url" content="{{ url(request()->path()) }}" />
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100">
    <div class="max-w-md w-full space-y-8">
        <!-- Header with Enhanced Animation -->
        <div class="text-center" data-aos="fade-down">
            <div class="flex justify-center mb-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="bg-gradient-to-br from-green-500 via-emerald-600 to-teal-600 w-20 h-20 rounded-2xl flex items-center justify-center shadow-2xl transform hover:rotate-6 transition-all duration-500 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl blur-xl opacity-50 animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <h2 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-3" data-aos="fade-up" data-aos-delay="200">
                {{ $title }} Portal
            </h2>
            <p class="mt-2 text-lg text-gray-700" data-aos="fade-up" data-aos-delay="300">
                Welcome to <span class="font-bold text-green-600">{{ $subdivision->name }}</span>
            </p>
        </div>

        <!-- Subdivision Info Card with Enhanced Design -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-green-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="400">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full -mr-16 -mt-16 opacity-50"></div>
            <div class="flex items-center relative z-10">
                <div class="flex-shrink-0 bg-gradient-to-br from-green-100 to-emerald-100 p-4 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xl font-bold text-gray-900 mb-1">{{ $subdivision->name }}</p>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-semibold">{{ $subdivision->code }}</span>
                    </div>
                    @if($subdivision->company)
                        <p class="text-sm text-gray-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $subdivision->company->name }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Login Form with Enhanced Design -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100" data-aos="fade-up" data-aos-delay="500">
            <form method="POST" action="{{ route($authRoute) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="subdivision_id" value="{{ $subdivision->id }}">

                <!-- Session Status -->
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="username" name="username" type="text" required autofocus
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="Enter your username"
                               value="{{ old('username') }}">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                               placeholder="Enter your password">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-green-600 hover:text-green-700">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button with Enhanced Design -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 hover:from-green-700 hover:via-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 transform hover:scale-105 relative overflow-hidden group">
                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 transform -skew-x-12 group-hover:translate-x-full transition-all duration-1000"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span class="relative z-10">Sign In to Dashboard</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Subdivision Selection -->
        <div class="text-center" data-aos="fade-up" data-aos-delay="600">
            <a href="{{ route($selectRoute) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white rounded-xl font-semibold text-gray-700 hover:text-gray-900 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-2 border-gray-200 hover:border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Choose Different Subdivision</span>
            </a>
        </div>

        <!-- Help Text with Enhanced Design -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-2xl p-6 shadow-md" data-aos="fade-up" data-aos-delay="700">
            <div class="flex items-start">
                <div class="flex-shrink-0 bg-blue-100 p-3 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-bold text-blue-900 mb-2">Need Help?</h4>
                    <p class="text-sm text-blue-800 leading-relaxed">
                        Your login credentials are managed by the system administrator. If you've forgotten your password or need assistance, please contact the admin team.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
