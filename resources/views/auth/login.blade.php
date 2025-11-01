@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header Section -->
        <div class="animate-fade-in-up">
            <div class="flex justify-center">
                <img src="{{ asset('images/mfn-logo.png') }}" alt="MFN Logo" class="h-32 w-auto animate-float">
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-gray-600 dark:text-gray-300">
                Meter Flow Nation ( mepco ) - Electricity Connection Management
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="rounded-lg bg-green-50 text-green-800 border border-green-200 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <div class="animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input 
                                id="email" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-300 dark:border-red-600 @enderror" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="email"
                                placeholder="you@example.com"
                            />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <input 
                                id="password" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('password') border-red-300 dark:border-red-600 @enderror" 
                                type="password"
                                name="password"
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700" name="remember">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a class="font-medium text-blue-600 hover:text-blue-500 transition" href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-white font-medium bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200"></i>
                            </span>
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection