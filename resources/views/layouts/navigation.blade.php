<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/mfn-logo.png') }}" alt="Meter Flow Nation (MEPCO) logo - Electricity connection management services" class="h-10 w-auto">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold dark:text-white text-black">Meter Flow Nation ( Mepco )</span>
                            <span class="text-xs text-gray-300">Supported by Mepco</span>
                        </div>
                    </a>
                </div>

                @if(Auth::check())
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        
                        <x-nav-link :href="route('user-form')" :active="request()->routeIs('user-form')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300">
                            {{ __('New Meter Request') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('track')" :active="request()->routeIs('track')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105">
                            {{ __('Track Application') }}
                        </x-nav-link>
                        @php $authUser = Auth::user(); @endphp
                        @if($authUser)
                            @if(method_exists($authUser, 'isAdmin') && $authUser->isAdmin())
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.global-summaries.index')" :active="request()->routeIs('admin.global-summaries.*')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105">
                                    {{ __('Global Summaries') }}
                                </x-nav-link>
                            @elseif(method_exists($authUser, 'isLS') && $authUser->isLS())
                                <x-nav-link :href="route('ls.dashboard')" :active="request()->routeIs('ls.dashboard')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @else
                                <x-nav-link :href="route('user.panel')" :active="request()->routeIs('user.panel')" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition duration-300 hover:border-blue-500 hover:text-blue-600 transform hover:scale-105">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            @if(Auth::check())
                <!-- Dark Mode Toggle -->
                <div class="hidden sm:flex sm:items-center sm:me-4">
                    <button id="darkModeToggle" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <div class="flex items-center">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 w-8 h-8 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-white font-medium text-sm">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-medium">{{ Auth::user()->name ?? 'User' }}</div>
                                        @php
                                            $user = Auth::user();
                                            $roleNames = [
                                                'ls' => 'LS',
                                                'sdc' => 'SDC',
                                                'ro' => 'RO',
                                                'admin' => 'Admin',
                                            ];
                                            $roleName = $roleNames[$user->role] ?? strtoupper($user->role ?? '');
                                            
                                            // Get current subdivision for LS/SDC/RO
                                            $currentSubdivision = null;
                                            if ($user->role === 'ls') {
                                                $currentSubdivisionId = session('current_subdivision_id');
                                                if ($currentSubdivisionId) {
                                                    $currentSubdivision = \App\Models\Subdivision::find($currentSubdivisionId);
                                                } elseif ($user->lsSubdivisions->count() > 0) {
                                                    $currentSubdivision = $user->lsSubdivisions->first();
                                                }
                                            } elseif (in_array($user->role, ['sdc', 'ro'])) {
                                                $currentSubdivisionId = session('current_subdivision_id');
                                                if ($currentSubdivisionId) {
                                                    $currentSubdivision = $user->subdivisions()->find($currentSubdivisionId);
                                                } elseif ($user->subdivisions->count() > 0) {
                                                    $currentSubdivision = $user->subdivisions->first();
                                                }
                                            }
                                        @endphp
                                        @if($roleName)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $roleName }}
                                                @if($currentSubdivision)
                                                    | {{ $currentSubdivision->name }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @php $authUser = Auth::user(); @endphp
                            @if($authUser)
                                @if(method_exists($authUser, 'isAdmin') && $authUser->isAdmin())
                                    <x-dropdown-link :href="route('admin.dashboard')" class="flex items-center hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 9h18v12H3z" />
                                        </svg>
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>
                                @elseif(method_exists($authUser, 'isLS') && $authUser->isLS())
                                    <x-dropdown-link :href="route('ls.dashboard')" class="flex items-center hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 9h18v12H3z" />
                                        </svg>
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>
                                @else
                                    <x-dropdown-link :href="route('user.panel')" class="flex items-center hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 9h18v12H3z" />
                                        </svg>
                                        {{ __('Dashboard') }}
                                    </x-dropdown-link>
                                @endif
                            @endif
                            <x-dropdown-link :href="route('user-form')" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('New Meter Request') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('track')" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                {{ __('Track Application') }}
                            </x-dropdown-link>
                            
                            @if(method_exists(Auth::user(), 'isLS') && Auth::user()->isLS())
                                <x-dropdown-link :href="route('ls.dashboard')" class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    {{ __('LS Dashboard') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <!-- Dark Mode Toggle -->
                <div class="hidden sm:flex sm:items-center sm:me-4">
                    <button id="darkModeToggle" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                        <!-- Sun icon (shown in dark mode) -->
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon (shown in light mode) -->
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
                <!-- If Not Authenticated: show static pages only -->
                <div class="hidden sm:flex sm:items-center sm:gap-6">
                    <a href="{{ route('about') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition duration-300">
                        {{ __('About') }}
                    </a>
                    <a href="{{ route('terms') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition duration-300">
                        {{ __('Terms') }}
                    </a>
                    <a href="{{ route('privacy') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition duration-300">
                        {{ __('Privacy') }}
                    </a>
                </div>
            @endif

            <!-- Hamburger for mobile -->
            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <button id="darkModeToggleMobile" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg id="sunIconMobile" class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg id="moonIconMobile" class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @if(Auth::check())
            <div class="pt-2 pb-3 space-y-1">
                <button id="darkModeToggleMobileMenu" type="button" class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <svg id="sunIconMobileMenu" class="h-5 w-5 mr-2 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="moonIconMobileMenu" class="h-5 w-5 mr-2 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span id="darkModeToggleText">Toggle Dark Mode</span>
                </button>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('user-form')" :active="request()->routeIs('user-form')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('New Meter Request') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('track')" :active="request()->routeIs('track')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    {{ __('Track Application') }}
                </x-responsive-nav-link>
                
                @if(method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.global-summaries.index')" :active="request()->routeIs('admin.global-summaries.*')" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        {{ __('Global Summaries') }}
                    </x-responsive-nav-link>
                @endif
                
                @if(method_exists(Auth::user(), 'isLS') && Auth::user()->isLS())
                    <x-responsive-nav-link :href="route('ls.dashboard')" :active="request()->routeIs('ls.dashboard')" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('LS Dashboard') }}
                    </x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4 flex items-center">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white font-medium">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div>
                        @php
                            $user = Auth::user();
                            $roleNames = [
                                'ls' => 'LS',
                                'sdc' => 'SDC',
                                'ro' => 'RO',
                                'admin' => 'Admin',
                            ];
                            $roleName = $roleNames[$user->role] ?? strtoupper($user->role ?? '');
                            
                            // Get current subdivision for LS/SDC/RO
                            $currentSubdivision = null;
                            if ($user->role === 'ls') {
                                $currentSubdivisionId = session('current_subdivision_id');
                                if ($currentSubdivisionId) {
                                    $currentSubdivision = \App\Models\Subdivision::find($currentSubdivisionId);
                                } elseif ($user->lsSubdivisions->count() > 0) {
                                    $currentSubdivision = $user->lsSubdivisions->first();
                                }
                            } elseif (in_array($user->role, ['sdc', 'ro'])) {
                                $currentSubdivisionId = session('current_subdivision_id');
                                if ($currentSubdivisionId) {
                                    $currentSubdivision = $user->subdivisions()->find($currentSubdivisionId);
                                } elseif ($user->subdivisions->count() > 0) {
                                    $currentSubdivision = $user->subdivisions->first();
                                }
                            }
                        @endphp
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="font-medium text-sm text-gray-500">
                            @if($roleName)
                                {{ $roleName }}
                                @if($currentSubdivision)
                                    | {{ $currentSubdivision->name }}
                                @endif
                            @else
                                {{ Auth::user()->email ?? '' }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" 
                            onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-2 pb-3 space-y-1">
                <button id="darkModeToggleMobileMenu" type="button" class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <svg id="sunIconMobileMenu" class="h-5 w-5 mr-2 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="moonIconMobileMenu" class="h-5 w-5 mr-2 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span id="darkModeToggleText">Toggle Dark Mode</span>
                </button>
                <x-responsive-nav-link :href="route('about')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('About') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('terms')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A1 1 0 0114 4l5 5a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Terms') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('privacy')" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.343 3-3 3S6 12.657 6 11s1.343-3 3-3 3 1.343 3 3z" />
                    </svg>
                    {{ __('Privacy') }}
                </x-responsive-nav-link>
            </div>
        @endif
    </div>
</nav>