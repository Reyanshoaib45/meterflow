@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header Section -->
        <div class="animate-fade-in-up">
            <div class="flex justify-center">
                <img src="{{ asset('images/mfn-logo.png') }}" alt="MFN Logo" class="h-32 w-auto animate-float">
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Forgot Password?
            </h2>
            <p class="mt-2 text-center text-gray-600">
                Contact Administrator for Password Reset
            </p>
        </div>

        <!-- Contact Admin Card -->
        <div class="animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-200">
                <!-- Info Icon -->
                <div class="flex justify-center mb-6">
                    <div class="bg-blue-50 rounded-full p-4">
                        <i class="fas fa-info-circle text-blue-600 text-4xl"></i>
                    </div>
                </div>

                <!-- Message -->
                <div class="text-center space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900">Password Reset Not Available</h3>
                    <p class="text-gray-600">
                        For security reasons, password resets are handled by the system administrator.
                    </p>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 text-left rounded">
                        <div class="flex items-start">
                            <i class="fas fa-user-shield text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-blue-900 mb-2">
                                    Contact System Administrator:
                                </p>
                                <ul class="text-sm text-blue-800 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                        <span>admin@mepco.gov.pk</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-phone mr-2 text-blue-600"></i>
                                        <span>1800-MFN-HELP</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back to Login Button -->
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-white font-medium bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-arrow-left text-blue-300 group-hover:text-blue-200"></i>
                        </span>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
