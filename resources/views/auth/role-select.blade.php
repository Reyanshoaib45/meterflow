@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-16 px-4 bg-gradient-to-br from-blue-50 via-white to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900" style="background-image: radial-gradient(circle, rgba(148, 163, 184, 0.1) 1px, transparent 1px); background-size: 30px 30px;">
    <div class="max-w-6xl w-full">
        <div class="text-center mb-12">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-4">Welcome to MEPCO System</h1>
            <p class="text-xl text-gray-700 dark:text-gray-300 mt-2">Select your portal to continue</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('login') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-shield text-2xl text-gray-900 dark:text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Admin Login</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Access admin dashboard and controls</p>
                </div>
            </a>

            <a href="{{ route('ls.select-subdivision') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-hard-hat text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Line Superintendent Login</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Company/Subdivision Line Superintendent Portal</p>
                </div>
            </a>

            <a href="{{ route('login') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">User Login</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Sign in to your user account</p>
                </div>
            </a>

            <a href="{{ route('register') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">User Signup</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Create a new user account</p>
                </div>
            </a>

            <a href="{{ route('sdc.select-subdivision') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                            <rect x="4" y="6" width="16" height="2" rx="0.5"/>
                            <rect x="4" y="11" width="16" height="2" rx="0.5"/>
                            <rect x="4" y="16" width="16" height="2" rx="0.5"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">SDC Login</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Smart Data Center Portal</p>
                </div>
            </a>

            <a href="{{ route('ro.select-subdivision') }}" class="group block rounded-xl p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full h-16 w-16 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-dollar-sign text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">RO Login</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm">Revenue Officer Portal</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
