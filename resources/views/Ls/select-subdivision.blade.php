@extends('layouts.app')

@php
    $routeName = request()->route()->getName();
    $roleType = 'LS';
    $loginRoute = 'ls.login';
    $title = 'LS Login Portal';
    $roleName = 'Line Superintendent';
    
    if (str_contains($routeName, 'sdc')) {
        $roleType = 'SDC';
        $loginRoute = 'sdc.login';
        $title = 'SDC Login Portal';
        $roleName = 'Smart Data Center';
    } elseif (str_contains($routeName, 'ro')) {
        $roleType = 'RO';
        $loginRoute = 'ro.login';
        $title = 'RO Login Portal';
        $roleName = 'Revenue Officer';
    }
@endphp

@section('title', $title . ' - Select Your Subdivision')

@section('canonical')
    <link rel="canonical" href="{{ url(request()->path()) }}" />
@endsection

@section('meta')
    <meta name="description" content="{{ $roleType }} ({{ $roleName }}) login portal for MEPCO subdivisions. Secure access for authorized personnel." />
    <meta name="keywords" content="MEPCO {{ $roleType }} login, {{ $roleName }} portal, subdivision management, MEPCO admin" />
    <meta name="robots" content="noindex, nofollow" />
    <meta property="og:title" content="{{ $title }} - MEPCO Subdivision Management" />
    <meta property="og:description" content="Secure login portal for {{ $roleName }}s to manage subdivision operations." />
    <meta property="og:url" content="{{ url(request()->path()) }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ asset('images/mfn-logo.png') }}" />
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl w-full">
        <!-- Header with Enhanced Animation -->
        <div class="text-center mb-16" data-aos="fade-down" data-aos-duration="800">
            <div class="flex justify-center mb-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="bg-gradient-to-br from-green-500 via-emerald-600 to-teal-600 w-28 h-28 rounded-3xl flex items-center justify-center shadow-2xl transform hover:rotate-6 hover:scale-110 transition-all duration-500 animate-pulse-border relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 rounded-3xl blur-xl opacity-50 animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent" data-aos="fade-up" data-aos-delay="300">
                {{ $title }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="400">
                Welcome, {{ $roleName }}! Select your subdivision to access the management dashboard
            </p>
            <div class="mt-6 flex justify-center gap-4 text-sm text-gray-500" data-aos="fade-up" data-aos-delay="500">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Secure Access</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Real-time Data</span>
                </div>
            </div>
        </div>

        <!-- Subdivisions Grid with Search -->
        @if($subdivisions->count() > 0)
            <!-- Search Bar -->
            <div class="mb-8 max-w-md mx-auto" data-aos="fade-up" data-aos-delay="600">
                <div class="relative">
                    <input type="text" id="subdivisionSearch" 
                           placeholder="Search subdivisions..." 
                           class="w-full px-6 py-4 rounded-2xl border-2 border-gray-200 focus:border-green-500 focus:outline-none focus:ring-4 focus:ring-green-100 transition-all duration-300 text-gray-700 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="subdivisionsGrid">
                @foreach($subdivisions as $index => $subdivision)
                    <a href="{{ route($loginRoute, ['subdivision' => $subdivision->id]) }}" 
                       data-subdivision-name="{{ strtolower($subdivision->name) }}"
                       data-subdivision-code="{{ strtolower($subdivision->code) }}"
                       class="subdivision-card group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 overflow-hidden border-2 border-transparent hover:border-green-500 relative"
                       data-aos="fade-up" 
                       data-aos-delay="{{ 100 + ($index * 100) }}">
                        
                        <!-- Animated Background Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-green-50 via-transparent to-emerald-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="p-8 relative z-10">
                            <!-- Icon with Animation -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="bg-gradient-to-br from-green-100 to-emerald-100 p-5 rounded-2xl group-hover:from-green-500 group-hover:to-emerald-600 transition-all duration-500 transform group-hover:scale-110 group-hover:rotate-3 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600 group-hover:text-white transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                                    Active
                                </div>
                            </div>

                            <!-- Subdivision Info -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300 line-clamp-2">
                                {{ $subdivision->name }}
                            </h3>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-sm font-semibold group-hover:bg-gray-200 transition-colors">
                                    {{ $subdivision->code }}
                                </span>
                            </div>
                            
                            @if($subdivision->company)
                                <div class="flex items-center text-sm text-gray-600 mb-4 group-hover:text-gray-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="font-medium">{{ $subdivision->company->name }}</span>
                                </div>
                            @endif

                            @if($subdivision->lsUser)
                                <div class="flex items-center text-sm text-gray-600 bg-gray-50 rounded-xl p-3 mb-4 group-hover:bg-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span><span class="font-semibold text-gray-700">LS:</span> {{ $subdivision->lsUser->name }}</span>
                                </div>
                            @endif

                            <!-- Stats with Enhanced Design -->
                            <div class="mt-6 pt-6 border-t-2 border-gray-100 group-hover:border-green-200 transition-colors">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center transform group-hover:scale-105 transition-transform duration-300">
                                        <p class="text-3xl font-bold text-green-600 mb-1">{{ $subdivision->applications_count ?? 0 }}</p>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Applications</p>
                                    </div>
                                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 text-center transform group-hover:scale-105 transition-transform duration-300">
                                        <p class="text-3xl font-bold text-blue-600 mb-1">{{ $subdivision->meters_count ?? 0 }}</p>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Meters</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Bottom Bar -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-4 group-hover:from-green-500 group-hover:to-emerald-600 transition-all duration-500 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 transform -skew-x-12 group-hover:translate-x-full transition-all duration-1000"></div>
                            <p class="text-sm font-bold text-green-700 group-hover:text-white flex items-center justify-center transition-colors duration-300 relative z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                <span>Click to Access Dashboard</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-xl p-16 text-center" data-aos="fade-up">
                <div class="mb-6">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Subdivisions Available</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">There are currently no subdivisions set up. Please contact the system administrator for assistance.</p>
                <a href="{{ route('landing') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Return to Home
                </a>
            </div>
        @endif

        <!-- Back to Home Button -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="700">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white rounded-xl font-semibold text-gray-700 hover:text-gray-900 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2 border-gray-200 hover:border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
</div>

<!-- Search Functionality Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('subdivisionSearch');
    const cards = document.querySelectorAll('.subdivision-card');
    
    if (searchInput && cards.length > 0) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            cards.forEach(card => {
                const name = card.getAttribute('data-subdivision-name') || '';
                const code = card.getAttribute('data-subdivision-code') || '';
                
                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in-up');
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show "no results" message if all cards are hidden
            const visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
            const grid = document.getElementById('subdivisionsGrid');
            
            let noResultsMsg = document.getElementById('noResultsMessage');
            
            if (visibleCards.length === 0 && searchTerm !== '') {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.id = 'noResultsMessage';
                    noResultsMsg.className = 'col-span-full text-center py-16';
                    noResultsMsg.innerHTML = `
                        <div class="bg-white rounded-2xl shadow-lg p-12 max-w-md mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Subdivisions Found</h3>
                            <p class="text-gray-600">Try adjusting your search terms</p>
                        </div>
                    `;
                    grid.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        });
    }
});
</script>
@endsection
