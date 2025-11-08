@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Error Icon -->
        <div class="mb-8 animate-fade-in-up" data-aos="zoom-in">
            <div class="inline-block relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-full p-8 shadow-2xl">
                    <svg class="w-32 h-32 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in-up" data-aos="fade-down" data-aos-delay="100">
            <h1 class="text-9xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                500
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8 space-y-3 animate-fade-in-up" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                Internal Server Error
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                Oops! Something went wrong on our end. Our team has been notified and we're working to fix the issue.
            </p>
        </div>

        <!-- Error Info Card -->
        <div class="bg-orange-50 dark:bg-orange-900/20 border-2 border-orange-200 dark:border-orange-800 rounded-2xl p-6 mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-300 mb-2">
                        What can you do?
                    </h3>
                    <ul class="text-orange-700 dark:text-orange-400 space-y-1 text-sm">
                        <li>• Try refreshing the page in a few moments</li>
                        <li>• Clear your browser cache and cookies</li>
                        <li>• Try again later - we're working on it!</li>
                        <li>• Contact support if the problem persists</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="400">
            <button onclick="location.reload()" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-xl shadow-lg hover:from-orange-700 hover:to-orange-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Page
            </button>
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

        <!-- Technical Status -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 animate-fade-in-up" data-aos="fade-up" data-aos-delay="500">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                <svg class="w-6 h-6 inline-block mr-2 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Technical Information
            </h3>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-left">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Error Code:</span>
                        <span class="ml-2 font-mono text-gray-900 dark:text-white">500</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Timestamp:</span>
                        <span class="ml-2 font-mono text-gray-900 dark:text-white">{{ now()->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                            <span class="w-2 h-2 bg-orange-600 rounded-full mr-2 animate-pulse"></span>
                            Error Logged - Team Notified
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center animate-fade-in-up" data-aos="fade-up" data-aos-delay="600">
            <p class="text-gray-600 dark:text-gray-400 mb-3">
                Need immediate assistance? Contact our support team
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
