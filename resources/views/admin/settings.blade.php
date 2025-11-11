@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['name' => 'Website Settings', 'url' => '']
    ]" />

    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">
            <i class="fas fa-cog text-blue-600"></i> Website Settings
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-300">Control website speed and maintenance mode</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <!-- Website Speed Control -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-3">
                        <i class="fas fa-tachometer-alt text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Website Speed</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Control how fast the website loads</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Low Speed -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="website_speed" value="low" 
                            {{ old('website_speed', $settings['website_speed']) == 'low' ? 'checked' : '' }}
                            class="peer sr-only">
                        <div class="p-5 border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 hover:border-red-300 transition">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-turtle text-3xl text-red-500"></i>
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                </div>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Low Speed</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Slower loading (5-10s delay)</p>
                        </div>
                    </label>

                    <!-- Medium Speed -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="website_speed" value="medium" 
                            {{ old('website_speed', $settings['website_speed']) == 'medium' ? 'checked' : '' }}
                            class="peer sr-only">
                        <div class="p-5 border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 hover:border-yellow-300 transition">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-walking text-3xl text-yellow-500"></i>
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Medium Speed</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Normal loading (2-3s delay)</p>
                        </div>
                    </label>

                    <!-- High Speed -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="website_speed" value="high" 
                            {{ old('website_speed', $settings['website_speed']) == 'high' ? 'checked' : '' }}
                            class="peer sr-only">
                        <div class="p-5 border-2 border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-300 transition">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-rocket text-3xl text-green-500"></i>
                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            </div>
                            <h4 class="font-bold text-gray-900 dark:text-white">High Speed</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Fast loading (no delay)</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-lg mr-3">
                            <i class="fas fa-tools text-2xl text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Maintenance Mode</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Show maintenance page to visitors</p>
                        </div>
                    </div>

                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" 
                            {{ old('maintenance_mode', $settings['maintenance_mode']) == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 dark:peer-focus:ring-orange-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-orange-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                            <span class="peer-checked:hidden">Off</span>
                            <span class="hidden peer-checked:inline">On</span>
                        </span>
                    </label>
                </div>

                <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-3 mt-1"></i>
                        <div class="text-sm text-orange-800 dark:text-orange-200">
                            <p class="font-semibold mb-1">Warning!</p>
                            <p>When maintenance mode is ON, visitors will see a maintenance page. Only you (admin) can access the website.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Display Option -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg mr-3">
                        <i class="fas fa-eye text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Maintenance Display</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Show maintenance message below loading screen</p>
                    </div>
                </div>

                <label class="flex items-center justify-between p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-purple-400 dark:hover:border-purple-600 transition cursor-pointer">
                    <div class="flex items-center">
                        <i class="fas fa-spinner fa-pulse text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Show after loading screen</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Display maintenance message below the loading animation</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_maintenance_below_loading" value="1" 
                            {{ old('show_maintenance_below_loading', $settings['show_maintenance_below_loading']) == '1' ? 'checked' : '' }}
                            class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                    </label>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Fix for radio button peer selector */
    input[type="radio"]:checked + div {
        border-color: currentColor;
    }
    
    input[name="website_speed"][value="low"]:checked + div {
        border-color: #ef4444;
        background-color: #fee2e2;
    }
    
    input[name="website_speed"][value="medium"]:checked + div {
        border-color: #eab308;
        background-color: #fef3c7;
    }
    
    input[name="website_speed"][value="high"]:checked + div {
        border-color: #22c55e;
        background-color: #dcfce7;
    }
    
    .dark input[name="website_speed"][value="low"]:checked + div {
        background-color: rgba(239, 68, 68, 0.2);
    }
    
    .dark input[name="website_speed"][value="medium"]:checked + div {
        background-color: rgba(234, 179, 8, 0.2);
    }
    
    .dark input[name="website_speed"][value="high"]:checked + div {
        background-color: rgba(34, 197, 94, 0.2);
    }
</style>
@endsection
