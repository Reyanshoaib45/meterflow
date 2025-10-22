@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-4">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 w-20 h-20 rounded-full flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">LS Login Portal</h1>
            <p class="text-lg text-gray-600">Select your subdivision to continue</p>
        </div>

        <!-- Subdivisions Grid -->
        @if($subdivisions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subdivisions as $subdivision)
                    <a href="{{ route('ls.login', ['subdivision' => $subdivision->id]) }}" 
                       class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border-2 border-transparent hover:border-green-500">
                        <div class="p-6">
                            <!-- Icon -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="bg-gradient-to-br from-green-100 to-emerald-100 p-4 rounded-lg group-hover:from-green-200 group-hover:to-emerald-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>

                            <!-- Subdivision Info -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                                {{ $subdivision->name }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                <span class="font-medium">Code:</span> {{ $subdivision->code }}
                            </p>
                            
                            @if($subdivision->company)
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $subdivision->company->name }}
                                </div>
                            @endif

                            @if($subdivision->lsUser)
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    LS: {{ $subdivision->lsUser->name }}
                                </div>
                            @endif

                            <!-- Stats -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <p class="text-2xl font-bold text-green-600">{{ $subdivision->applications_count ?? 0 }}</p>
                                        <p class="text-xs text-gray-500">Applications</p>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-blue-600">{{ $subdivision->meters_count ?? 0 }}</p>
                                        <p class="text-xs text-gray-500">Meters</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Bar -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-3 group-hover:from-green-100 group-hover:to-emerald-100 transition-colors">
                            <p class="text-sm font-medium text-green-700 flex items-center justify-center">
                                <span>Click to Login</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Subdivisions Available</h3>
                <p class="text-gray-600">Please contact the administrator to set up subdivisions.</p>
            </div>
        @endif

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('landing') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
