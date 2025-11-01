@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="animate-fade-in-up">
                <div class="flex justify-center">
                    <img src="{{ asset('images/mfn-logo.png') }}" alt="MFN Logo" class="h-32 w-auto animate-float">
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold text-gray-900 dark:text-white">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-gray-600 dark:text-gray-300">
                    Meter Flow Nation ( mepco ) - Electricity Connection Management
                </p>
            </div>

            <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="name" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                    type="text" 
                                    name="name" 
                                    :value="old('name')" 
                                    required 
                                    autofocus 
                                    autocomplete="name" 
                                    placeholder="John Doe"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="email" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autocomplete="username" 
                                    placeholder="you@example.com"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="password" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                    type="password"
                                    name="password"
                                    required 
                                    autocomplete="new-password"
                                    placeholder="••••••••"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300" />
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-text-input 
                                    id="password_confirmation" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                                    type="password"
                                    name="password_confirmation"
                                    required 
                                    autocomplete="new-password"
                                    placeholder="••••••••"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <a class="font-medium text-blue-600 hover:text-blue-500 transition" href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>
                            </div>

                            <div>
                                <x-primary-button class="ml-3 py-3 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection