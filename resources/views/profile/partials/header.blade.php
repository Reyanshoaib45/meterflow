<nav class="header-gradient shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-white font-semibold text-lg inline-flex items-center gap-2">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" fill="#fff" opacity="0.08" />
                    </svg>
                    <span>{{ config('app.name', 'Meter Flow Nation') }}</span>
                </a>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <a href="{{ route('landing') }}" class="text-white hover:text-blue-200">Home</a>
                <a href="{{ route('track') }}" class="text-white hover:text-blue-200">Track</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200">Dashboard</a>
                    <span class="text-white/80 px-3">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-blue-200">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200">Log in</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <div class="sm:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 rounded-md inline-flex items-center">
                    <svg x-show="!open" class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div x-show="open" x-transition
                    class="absolute right-4 mt-2 w-40 bg-white/5 backdrop-blur-md rounded-md p-3">
                    <a href="{{ route('landing') }}" class="block text-white py-1">Home</a>
                    <a href="{{ route('track') }}" class="block text-white py-1">Track</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-white py-1">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-white py-1">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-white py-1">Log in</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
