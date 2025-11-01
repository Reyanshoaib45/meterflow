@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-16 px-4">
    <div class="max-w-3xl w-full">
        <div class="text-center mb-10">
            <img src="{{ asset('images/mfn-logo.png') }}" alt="MEPCO Logo" class="h-24 w-auto mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome to MEPCO System</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Select your portal to continue</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('login') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Admin Login</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Access admin dashboard and controls</p>
                    </div>
                    <div class="bg-gradient-to-br from-gray-700 to-black text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('ls.select-subdivision') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">LS Login</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Company/Subdivision Line Superintendent</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('login') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">User Login</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Sign in to your user account</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('register') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">User Signup</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Create a new user account</p>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('sdc.select-subdivision') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">SDC Login</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Smart Data Center Portal</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('ro.select-subdivision') }}" class="group block rounded-2xl border border-gray-200 dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:shadow-xl transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">RO Login</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">Revenue Officer Portal</p>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-full h-12 w-12 flex items-center justify-center group-hover:scale-105 transition">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
