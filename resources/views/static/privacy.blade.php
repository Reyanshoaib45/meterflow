@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-16 right-16 w-32 h-32 bg-green-200 rounded-full opacity-10 animate-float"></div>
        <div class="absolute top-1/3 left-8 w-20 h-20 bg-blue-200 rounded-full opacity-15 animate-bounce"></div>
        <div class="absolute bottom-32 right-1/4 w-16 h-16 bg-purple-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-16 left-1/3 w-28 h-28 bg-teal-200 rounded-full opacity-10 animate-float" style="animation-delay: 1.5s;"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full mb-6 shadow-lg animate-pulse-border">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                Privacy Policy
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Your privacy is important to us. This policy explains how we collect, use, and protect your information.
            </p>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border border-gray-200 animate-slide-in-right">
            <div class="prose prose-lg max-w-none">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        Information We Collect
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We collect information you provide directly to us when using Meter Flow Nation ( mepco ) services:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>Personal information (name, CNIC, contact details)</li>
                        <li>Address and location information</li>
                        <li>Application details and preferences</li>
                        <li>Communication records and support requests</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        How We Use Your Information
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We use the information we collect to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>Process your electricity connection applications</li>
                        <li>Provide customer support and respond to inquiries</li>
                        <li>Send important updates about your applications</li>
                        <li>Improve our services and user experience</li>
                        <li>Comply with legal and regulatory requirements</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                        Information Sharing
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We do not sell, trade, or otherwise transfer your personal information to third parties except:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>With MEPCO officials for application processing</li>
                        <li>When required by law or legal process</li>
                        <li>To protect our rights and prevent fraud</li>
                        <li>With your explicit consent</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                        Data Security
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        We implement appropriate security measures to protect your personal information against 
                        unauthorized access, alteration, disclosure, or destruction. This includes:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4 mt-4">
                        <li>Secure data transmission using encryption</li>
                        <li>Regular security assessments and updates</li>
                        <li>Access controls and authentication measures</li>
                        <li>Staff training on data protection practices</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                        Your Rights
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        You have the right to:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                        <li>Access and review your personal information</li>
                        <li>Request corrections to inaccurate data</li>
                        <li>Request deletion of your personal information</li>
                        <li>Opt-out of non-essential communications</li>
                        <li>File complaints about our data practices</li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                        Cookies and Tracking
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        Our website may use cookies and similar technologies to enhance your experience. 
                        These help us understand how you use our services and improve functionality. 
                        You can control cookie settings through your browser preferences.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">7</span>
                        Data Retention
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        We retain your personal information only as long as necessary to provide our services 
                        and comply with legal obligations. Application data is typically retained for the 
                        duration of the connection process and as required by MEPCO regulations.
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-pink-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">8</span>
                        Contact Us
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        If you have questions about this Privacy Policy or our data practices, please contact us:
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
            <a href="{{ route('landing') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
