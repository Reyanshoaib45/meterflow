@extends('layouts.app')

@section('title', 'About Meter Flow Nation')

@section('canonical')
    <link rel="canonical" href="{{ url('/about') }}" />
@endsection

@section('meta')
    <meta name="description" content="Learn about Meter Flow Nation (mepco) — our mission, vision, services, and team." />
    <meta name="keywords" content="about mepco, meter flow nation, electricity services pakistan, mission vision mepco, team, services overview" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="About Meter Flow Nation" />
    <meta property="og:description" content="Empowering communities through reliable electricity connection management services." />
    <meta property="og:locale" content="en_PK" />
    <meta property="og:url" content="{{ url('/about') }}" />
    <meta property="og:type" content="article" />
    <meta property="og:image" content="{{ asset('images/mfn-logo.png') }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@" />
    <meta name="twitter:title" content="About Meter Flow Nation" />
    <meta name="twitter:description" content="Empowering communities through reliable electricity connection management services." />
    <meta name="twitter:image" content="{{ asset('images/mfn-logo.png') }}" />
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-pink-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-20 w-24 h-24 bg-purple-200 rounded-full opacity-20 animate-float"></div>
        <div class="absolute top-1/4 right-12 w-16 h-16 bg-pink-200 rounded-full opacity-25 animate-bounce"></div>
        <div class="absolute bottom-1/3 left-12 w-20 h-20 bg-blue-200 rounded-full opacity-15 animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-indigo-200 rounded-full opacity-10 animate-float" style="animation-delay: 2.5s;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16" data-aos="fade-down">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mb-8 shadow-lg animate-pulse-border" data-aos="zoom-in" data-aos-delay="100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6 bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent" data-aos="fade-up" data-aos-delay="200">
                About Us
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="300">
                Empowering communities through reliable electricity connection management services
            </p>
        </div>

        <!-- Mission Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <div data-aos="fade-right" data-aos-delay="100">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Our Mission</h2>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                        To provide seamless, efficient, and transparent electricity connection services to the people of Pakistan. 
                        We strive to bridge the gap between citizens and MEPCO, making electricity connection applications 
                        accessible, trackable, and hassle-free for everyone.
                    </p>
                </div>
            </div>

            <div data-aos="fade-left" data-aos-delay="100">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Our Vision</h2>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
                        To become the leading digital platform for electricity services in Pakistan, 
                        fostering a future where every citizen has easy access to reliable electricity connections 
                        through innovative technology and exceptional customer service.
                    </p>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mb-16">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">What We Offer</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Comprehensive electricity connection services designed with you in mind
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center border border-gray-200 dark:border-gray-700 hover-lift card-hover" data-aos="flip-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-gentle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Easy Applications</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Submit new electricity connection applications online with our user-friendly interface. 
                        No complex paperwork or long queues required.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center border border-gray-200 dark:border-gray-700 hover-lift card-hover" data-aos="flip-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-heartbeat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Real-time Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Track your application status in real-time with our advanced tracking system. 
                        Stay informed at every step of the process.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center border border-gray-200 dark:border-gray-700 hover-lift card-hover" data-aos="flip-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-glow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">24/7 Support</h3>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Get help whenever you need it with our round-the-clock customer support. 
                        We're here to assist you at every step.
                    </p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="mb-16 animate-fade-in-up">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Our Team</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Dedicated professionals working to serve you better
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-center text-center md:text-left">
                    <div class="w-32 h-32 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-6 md:mb-0 md:mr-8 animate-float">
                        <span class="text-4xl font-bold text-white">RS</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Reyan Shoaib</h3>
                        <p class="text-xl text-blue-600 dark:text-blue-400 mb-4">Lead Developer & Project Manager</p>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            Passionate about creating innovative solutions that make a real difference in people's lives. 
                            With expertise in web development and system design, Reyan leads the technical vision 
                            behind Meter Flow Nation ( mepco ).
                        </p>
                        <div class="flex justify-center md:justify-start space-x-4">
                            <a href="mailto:reyanshoaib45@gmail.com" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/in/reyan-shoaib-9582b3387/" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="mb-16 animate-fade-in-up">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Our Values</h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    The principles that guide everything we do
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Reliability</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Consistent, dependable service you can trust</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Innovation</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Cutting-edge solutions for modern challenges</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Community</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Serving the people of Pakistan with dedication</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center border border-gray-200 dark:border-gray-700 hover-lift">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Excellence</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Striving for the highest quality in everything</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center animate-fade-in-up" style="animation-delay: 0.3s;">
            <a href="{{ route('landing') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
