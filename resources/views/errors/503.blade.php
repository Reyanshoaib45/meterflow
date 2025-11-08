@extends('layouts.app')

@section('title', '503 - Service Unavailable')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Error Icon -->
        <div class="mb-8 animate-fade-in-up" data-aos="zoom-in">
            <div class="inline-block relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full blur-3xl opacity-30 animate-pulse"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-full p-8 shadow-2xl">
                    <svg class="w-32 h-32 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in-up" data-aos="fade-down" data-aos-delay="100">
            <h1 class="text-9xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                503
            </h1>
        </div>

        <!-- Error Message -->
        <div class="mb-8 space-y-3 animate-fade-in-up" data-aos="fade-up" data-aos-delay="200">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">
                Service Unavailable
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                We're currently performing maintenance to improve your experience. We'll be back shortly!
            </p>
        </div>

        <!-- Maintenance Info Card -->
        <div class="bg-purple-50 dark:bg-purple-900/20 border-2 border-purple-200 dark:border-purple-800 rounded-2xl p-6 mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-300 mb-2">
                        Scheduled Maintenance
                    </h3>
                    <ul class="text-purple-700 dark:text-purple-400 space-y-1 text-sm">
                        <li>• System upgrades and optimizations</li>
                        <li>• Database maintenance and backups</li>
                        <li>• Security updates and patches</li>
                        <li>• Estimated downtime: 30-60 minutes</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Status Display -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
            <p class="text-gray-600 dark:text-gray-300 font-medium">
                Maintenance in Progress...
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                Current Time: {{ now()->format('h:i A, M d, Y') }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-fade-in-up" data-aos="fade-up" data-aos-delay="500">
            <button onclick="location.reload()" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:from-purple-700 hover:to-purple-800 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Check Again
            </button>
            <button class="inline-flex items-center justify-center px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-semibold rounded-xl shadow-lg border-2 border-gray-300 dark:border-gray-600 hover:border-purple-500 dark:hover:border-purple-500 transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300" onclick="subscribeToUpdates()">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                Notify Me
            </button>
        </div>

        <!-- What You Can Do -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 animate-fade-in-up" data-aos="fade-up" data-aos-delay="600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                In the meantime...
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-center">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Read our documentation</p>
                </div>
                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-xl text-center">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Check status updates</p>
                </div>
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-center">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Follow us on social</p>
                </div>
            </div>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center animate-fade-in-up" data-aos="fade-up" data-aos-delay="700">
            <p class="text-gray-600 dark:text-gray-400 mb-3">
                Questions? Contact support
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

<script>
function subscribeToUpdates() {
    alert('We\'ll notify you when the service is back online!\nYou can also follow us on social media for real-time updates.');
}

// Auto-refresh every 60 seconds
let refreshInterval;
let countdown = 60;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        countdown--;
        if (countdown <= 0) {
            location.reload();
        }
    }, 1000);
}

// Uncomment to enable auto-refresh
// startAutoRefresh();
</script>
@endsection
