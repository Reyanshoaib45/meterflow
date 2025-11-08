@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Error Icon -->
        <div class="mb-8 animate-fade-in-up" data-aos="zoom-in">
            <div class="inline-block relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-full p-8 shadow-2xl">
                    <svg class="w-32 h-32 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in-up" data-aos="fade-down" data-aos-delay="100">
            <h1 class="text-9xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                404
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8 space-y-3 animate-fade-in-up" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                Page Not Found
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                Oops! The page you're looking for seems to have wandered off. It might have been moved or deleted.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Go Home
            </a>
            <button onclick="history.back()" class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg border-2 border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-500 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Go Back
            </button>
        </div>

        <!-- Helpful Links -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 animate-fade-in-up" data-aos="fade-up" data-aos-delay="400">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                Maybe you were looking for:
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('user-form') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                    <div class="bg-blue-600 rounded-lg p-3 mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Request New Meter</span>
                </a>
                <a href="{{ route('track') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                    <div class="bg-green-600 rounded-lg p-3 mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Track Application</span>
                </a>
                <a href="{{ route('file-complaint') }}" class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors group">
                    <div class="bg-red-600 rounded-lg p-3 mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">File Complaint</span>
                </a>
                <a href="{{ route('login') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                    <div class="bg-purple-600 rounded-lg p-3 mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Admin Login</span>
                </a>
            </div>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center animate-fade-in-up" data-aos="fade-up" data-aos-delay="500">
            <p class="text-gray-600 dark:text-gray-400 mb-3">
                Need help? Contact our support team
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
