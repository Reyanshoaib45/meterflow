@extends('layouts.app')

@section('title', '403 - Access Forbidden')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Error Icon -->
        <div class="mb-8 animate-fade-in-up" data-aos="zoom-in">
            <div class="inline-block relative">
                <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-orange-500 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-full p-8 shadow-2xl">
                    <svg class="w-32 h-32 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in-up" data-aos="fade-down" data-aos-delay="100">
            <h1 class="text-9xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                403
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8 space-y-3 animate-fade-in-up" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                Access Forbidden
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                Sorry, you don't have permission to access this page. This area is restricted to authorized users only.
            </p>
        </div>

        <!-- Warning Card -->
        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-2xl p-6 mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">
                        Why am I seeing this?
                    </h3>
                    <ul class="text-red-700 dark:text-red-400 space-y-1 text-sm">
                        <li>• You're not logged in to an authorized account</li>
                        <li>• Your account doesn't have sufficient permissions</li>
                        <li>• The page requires a specific user role (Admin, LS, SDC, RO)</li>
                        <li>• Your session may have expired</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Go Home
            </a>
            @guest
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl shadow-lg hover:from-green-700 hover:to-green-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login
                </a>
            @endguest
            <button onclick="history.back()" class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg border-2 border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-500 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </button>
        </div>

        <!-- Available Portals -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 animate-fade-in-up" data-aos="fade-up" data-aos-delay="500">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                Available Login Portals
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('login') }}" class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors text-center group">
                    <div class="bg-blue-600 rounded-lg p-3 mx-auto mb-3 w-fit group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium text-sm">Admin Login</span>
                </a>
                <a href="{{ route('ls.select-subdivision') }}" class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors text-center group">
                    <div class="bg-green-600 rounded-lg p-3 mx-auto mb-3 w-fit group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium text-sm">LS Portal</span>
                </a>
                <a href="{{ route('sdc.select-subdivision') }}" class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors text-center group">
                    <div class="bg-purple-600 rounded-lg p-3 mx-auto mb-3 w-fit group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium text-sm">SDC Portal</span>
                </a>
                <a href="{{ route('ro.select-subdivision') }}" class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors text-center group">
                    <div class="bg-orange-600 rounded-lg p-3 mx-auto mb-3 w-fit group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium text-sm">RO Portal</span>
                </a>
            </div>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center animate-fade-in-up" data-aos="fade-up" data-aos-delay="600">
            <p class="text-gray-600 dark:text-gray-400 mb-3">
                Still having issues? Contact support
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 text-sm">
                <a href="tel:+923006380386" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    03006380386
                </a>
                <span class="text-gray-400 dark:text-gray-600 hidden sm:inline">|</span>
                <a href="mailto:meterflownation@gmail.com" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    meterflownation@gmail.com
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
