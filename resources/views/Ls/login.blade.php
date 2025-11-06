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
@php
    // Determine theme based on role
    $isDarkTheme = str_contains($routeName, 'sdc') || str_contains($routeName, 'ro');
    $bgGradient = $isDarkTheme 
        ? 'bg-gradient-to-br from-gray-900 via-slate-900 to-gray-800' 
        : 'bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100';
    $iconBg = $isDarkTheme 
        ? 'bg-gradient-to-br from-purple-500 via-indigo-600 to-purple-700' 
        : 'bg-gradient-to-br from-green-500 via-emerald-600 to-teal-600';
    $iconGlow = $isDarkTheme 
        ? 'bg-gradient-to-br from-purple-400 to-indigo-500' 
        : 'bg-gradient-to-br from-green-400 to-emerald-500';
    $titleGradient = $isDarkTheme 
        ? 'bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent' 
        : 'bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent';
    $welcomeText = $isDarkTheme ? 'text-gray-300' : 'text-gray-700';
    $subdivisionName = $isDarkTheme ? 'text-purple-400' : 'text-green-600';
    $cardBg = $isDarkTheme ? 'bg-gray-800/90 border-purple-500' : 'bg-white border-green-500';
    $cardGlow = $isDarkTheme ? 'from-purple-900/20 to-indigo-900/20' : 'from-green-100 to-emerald-100';
    $cardIconBg = $isDarkTheme ? 'from-purple-900/30 to-indigo-900/30' : 'from-green-100 to-emerald-100';
    $cardIcon = $isDarkTheme ? 'text-purple-400' : 'text-green-600';
    $cardText = $isDarkTheme ? 'text-white' : 'text-gray-900';
    $cardCodeBg = $isDarkTheme ? 'bg-purple-900/50 text-purple-300' : 'bg-green-100 text-green-700';
    $cardCompanyText = $isDarkTheme ? 'text-gray-400' : 'text-gray-600';
    $formBg = $isDarkTheme ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100';
    $inputBg = $isDarkTheme ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500';
    $labelText = $isDarkTheme ? 'text-gray-300' : 'text-gray-700';
    $buttonGradient = $isDarkTheme 
        ? 'from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:via-indigo-700 hover:to-purple-800 focus:ring-purple-300' 
        : 'from-green-600 via-emerald-600 to-teal-600 hover:from-green-700 hover:via-emerald-700 hover:to-teal-700 focus:ring-green-300';
@endphp

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 {{ $bgGradient }}">
    <div class="max-w-md w-full space-y-8">
        <!-- Header with Enhanced Animation -->
        <div class="text-center" data-aos="fade-down">
            <div class="flex justify-center mb-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="{{ $iconBg }} w-20 h-20 rounded-2xl flex items-center justify-center shadow-2xl transform hover:rotate-6 transition-all duration-500 relative">
                    <div class="absolute inset-0 {{ $iconGlow }} rounded-2xl blur-xl opacity-50 animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <h2 class="text-4xl font-bold {{ $titleGradient }} mb-3" data-aos="fade-up" data-aos-delay="200">
                {{ $title }} Portal
            </h2>
            <p class="mt-2 text-lg {{ $welcomeText }}" data-aos="fade-up" data-aos-delay="300">
                Welcome to <span class="font-bold {{ $subdivisionName }}">{{ $subdivision->name }}</span>
            </p>
        </div>

        <!-- Subdivision Info Card with Enhanced Design -->
        <div class="{{ $cardBg }} rounded-2xl shadow-lg p-6 border-2 relative overflow-hidden" data-aos="fade-up" data-aos-delay="400">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $cardGlow }} rounded-full -mr-16 -mt-16 opacity-50"></div>
            <div class="flex items-center relative z-10">
                <div class="flex-shrink-0 bg-gradient-to-br {{ $cardIconBg }} p-4 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 {{ $cardIcon }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-xl font-bold {{ $cardText }} mb-1">{{ $subdivision->name }}</p>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="{{ $cardCodeBg }} px-2 py-1 rounded-lg text-xs font-semibold">{{ $subdivision->code }}</span>
                    </div>
                    @if($subdivision->company)
                        <p class="text-sm {{ $cardCompanyText }} flex items-center gap-1">
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
        <div class="{{ $formBg }} rounded-2xl shadow-2xl p-8 border" data-aos="fade-up" data-aos-delay="500">
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

                <!-- Username or Email -->
                <div>
                    <label for="username" class="block text-sm font-medium {{ $labelText }} mb-2">
                        Username or Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $isDarkTheme ? 'text-gray-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="username" name="username" type="text" required autofocus
                               class="block w-full pl-10 pr-3 py-3 {{ $inputBg }} border rounded-lg focus:ring-2 {{ $isDarkTheme ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-transparent' }} transition"
                               placeholder="Enter your username or email"
                               value="{{ old('username') }}">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium {{ $labelText }} mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $isDarkTheme ? 'text-gray-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="block w-full pl-10 pr-3 py-3 {{ $inputBg }} border rounded-lg focus:ring-2 {{ $isDarkTheme ? 'focus:ring-purple-500 focus:border-purple-500' : 'focus:ring-green-500 focus:border-transparent' }} transition"
                               placeholder="Enter your password">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                               class="h-4 w-4 {{ $isDarkTheme ? 'text-purple-600 focus:ring-purple-500 border-gray-600 bg-gray-700' : 'text-green-600 focus:ring-green-500 border-gray-300' }} rounded">
                        <label for="remember_me" class="ml-2 block text-sm {{ $labelText }}">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium {{ $isDarkTheme ? 'text-purple-400 hover:text-purple-300' : 'text-green-600 hover:text-green-700' }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button with Enhanced Design -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-gradient-to-r {{ $buttonGradient }} focus:outline-none focus:ring-4 transition-all duration-300 transform hover:scale-105 relative overflow-hidden group">
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
