@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center mb-16 mt-8">
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Welcome to MEPCO
        </h1>
        <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Your trusted partner for electricity connection management
        </p>
    </div>
    
    <!-- Main Services Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16 max-w-4xl mx-auto">
        <!-- New Application Card -->
        <div class="bg-white rounded-2xl shadow-xl p-10 text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border-2 border-blue-100 hover:border-blue-300">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Submit Application</h3>
            <p class="text-gray-600 mb-8 leading-relaxed text-lg">Apply for a new electricity connection quickly and easily. No login required!</p>
            <a href="{{ route('user-form') }}" class="inline-block bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Apply Now
                </span>
            </a>
        </div>
        
        <!-- Track Application Card -->
        <div class="bg-white rounded-2xl shadow-xl p-10 text-center transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border-2 border-green-100 hover:border-green-300">
            <div class="bg-gradient-to-br from-green-500 to-green-600 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Track Application</h3>
            <p class="text-gray-600 mb-8 leading-relaxed text-lg">Check the status of your application in real-time using your application number</p>
            <a href="{{ route('track') }}" class="inline-block bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Track Now
                </span>
            </a>
        </div>
    </div>
    
    <!-- Stats Section -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 mb-16 border border-gray-200">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Choose MEPCO?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Support</h3>
                <p class="text-gray-600">Round-the-clock customer service</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 mb-2">99%</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Reliability</h3>
                <p class="text-gray-600">Consistent service delivery</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-purple-600 mb-2">10K+</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Customers</h3>
                <p class="text-gray-600">Satisfied users nationwide</p>
            </div>
        </div>
    </div>
    
    <!-- Help Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-center text-white mb-16">
        <h2 class="text-3xl font-bold mb-4">Need Help?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">Our customer support team is available 24/7 to assist you with any questions or issues.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <div class="flex items-center justify-center bg-white bg-opacity-20 rounded-lg px-6 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="font-medium text-lg">1800-MEPCO-HELP</span>
            </div>
            <div class="flex items-center justify-center bg-white bg-opacity-20 rounded-lg px-6 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-medium text-lg">support@mepco.gov</span>
            </div>
        </div>
    </div>
</div>
@endsection