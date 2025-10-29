@extends('layouts.app')

@section('title', 'Terms and Conditions')

@section('canonical')
    <link rel="canonical" href="{{ url('/terms') }}" />
@endsection

@section('meta')
    <meta name="description" content="Please read the terms and conditions for using Meter Flow Nation (mepco) services." />
    <meta name="keywords" content="mepco terms, terms and conditions, service terms, electricity services pakistan, meter flow nation terms" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="Terms and Conditions" />
    <meta property="og:description" content="Terms governing the use of electricity connection services provided by Meter Flow Nation." />
    <meta property="og:locale" content="en_PK" />
    <meta property="og:url" content="{{ url('/terms') }}" />
    <meta property="og:type" content="article" />
    <meta property="og:image" content="{{ asset('images/mfn-logo.png') }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@" />
    <meta name="twitter:title" content="Terms and Conditions" />
    <meta name="twitter:description" content="Terms governing the use of electricity connection services provided by Meter Flow Nation." />
    <meta name="twitter:image" content="{{ asset('images/mfn-logo.png') }}" />
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 animate-float"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-purple-200 rounded-full opacity-20 animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-40 right-1/3 w-24 h-24 bg-yellow-200 rounded-full opacity-20 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-lg animate-pulse-border">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Terms and Conditions
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Please read these terms and conditions carefully before using Meter Flow Nation ( mepco ) services.
            </p>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border border-gray-200 animate-slide-in-left">
            <div class="prose prose-lg max-w-none">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        Acceptance of Terms
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        By accessing and using Meter Flow Nation ( mepco ) services, you accept and agree to be bound by the terms and provision of this agreement. 
                        If you do not agree to abide by the above, please do not use this service.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        Service Description
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Meter Flow Nation ( mepco ) provides electricity connection management services including:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>New electricity connection applications</li>
                        <li>Application status tracking</li>
                        <li>Complaint registration and management</li>
                        <li>Customer support services</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                        User Responsibilities
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Users are responsible for:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>Providing accurate and complete information</li>
                        <li>Maintaining the confidentiality of their application numbers</li>
                        <li>Using the service in accordance with applicable laws</li>
                        <li>Respecting the rights of other users</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                        Privacy and Data Protection
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        We are committed to protecting your privacy. All personal information collected through our services 
                        is handled in accordance with our Privacy Policy and applicable data protection laws. 
                        Your data will only be used for the purpose of providing electricity connection services.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                        Limitation of Liability
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        Meter Flow Nation ( mepco ) shall not be liable for any indirect, incidental, special, consequential, 
                        or punitive damages, including without limitation, loss of profits, data, use, goodwill, 
                        or other intangible losses resulting from your use of the service.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                        Modifications to Terms
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        We reserve the right to modify these terms at any time. Changes will be effective immediately 
                        upon posting on this page. Your continued use of the service after any changes constitutes 
                        acceptance of the new terms.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">7</span>
                        Contact Information
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you have any questions about these Terms and Conditions, please contact us at:
                    </p>
                    <div class="bg-gray-50 rounded-lg p-4 mt-4">
                        <p class="text-gray-700"><strong>Email:</strong> reyanshoaib45@gmail.com</p>
                        <p class="text-gray-700"><strong>Phone:</strong> 03464769301</p>
                        <p class="text-gray-700"><strong>Address:</strong> Multan, Punjab, Pakistan</p>
                    </div>
                </div>
            </div>

            <!-- Last Updated -->
            <div class="border-t border-gray-200 pt-6 mt-8">
                <p class="text-sm text-gray-500 text-center">
                    Last updated: {{ date('F j, Y') }}
                </p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8 animate-fade-in-up" style="animation-delay: 0.3s;">
            <a href="{{ route('landing') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
