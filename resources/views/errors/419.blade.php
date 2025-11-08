@extends('layouts.app')

@section('title', '419 - Session Expired')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <div class="mb-8 animate-fade-in-up" data-aos="zoom-in">
            <div class="inline-block relative">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-full p-8 shadow-2xl">
                    <svg class="w-32 h-32 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="mb-6 animate-fade-in-up" data-aos="fade-down" data-aos-delay="100">
            <h1 class="text-9xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">
                419
            </h1>
        </div>

        <div class="mb-8 space-y-3 animate-fade-in-up" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                Session Expired
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                Your session has expired for security reasons. Please refresh the page and try again.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="300">
            <button onclick="location.reload()" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-600 to-yellow-700 text-white font-semibold rounded-xl shadow-lg hover:from-yellow-700 hover:to-yellow-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh & Retry
            </button>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Start Fresh
            </a>
        </div>
    </div>
</div>
@endsection
