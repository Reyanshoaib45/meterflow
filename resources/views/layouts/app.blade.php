<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @hasSection('title')
        <title>@yield('title') - {{ config('app.name', 'Meter Flow Nation ( mepco )') }}</title>
    @else
        <title>{{ config('app.name', 'Meter Flow Nation ( mepco )') }}</title>
    @endif

    @hasSection('canonical')
        @yield('canonical')
    @endif
    @hasSection('meta')
        @yield('meta')
    @endif
    @stack('meta')

    <!-- Default OG site name -->
    <meta property="og:site_name" content="{{ config('app.name', 'Meter Flow Nation ( mepco )') }}" />

    <!-- Performance hints -->
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="dns-prefetch" href="//code.jquery.com">

    <!-- Preload AOS CSS to reduce render blocking -->
    <link rel="preload" href="https://unpkg.com/aos@2.3.1/dist/aos.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"></noscript>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mfn-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/mfn-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/mfn-logo.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/mfn-logo.png') }}" imagesrcset="{{ asset('images/mfn-logo.png') }} 1x" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Custom Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-animations.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased">
     @include('layouts.navigation')
    <!-- Background Pattern -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-blue-50 to-indigo-100 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIyIi8+PC9nPjwvZz48L3N2Zz4=')] opacity-20"></div>
    </div>

    <!-- Page Content -->
    <div>
        @yield('content')
    </div>

    <!-- Flash Messages -->
    @if (session('status'))
        <div class="fixed top-4 right-4 z-50 animate-fade-in-up">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-lg max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 animate-fade-in-up">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg max-w-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

         {{-- Footer partial --}}
    @include('profile.partials.footer')

    {{-- Alpine (for progressive unlock + small interactions). If you use Vite, you can import Alpine there instead. --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    
    <!-- Custom Scripts -->
    <script src="{{ asset('js/custom-scripts.js') }}" defer></script>
    
    <!-- Infinite Scroll Script -->
    <script src="{{ asset('js/infinite-scroll.js') }}" defer></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    
    <!-- Particles.js will be loaded conditionally below -->
    
    <!-- Initialize Libraries -->
    <script>
        // Initialize AOS with enhanced settings (idle for smoother main thread)
        const initAOS = () => AOS && AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true,
            offset: 120,
            delay: 30,
            anchorPlacement: 'top-bottom',
        });
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => initAOS());
        } else {
            window.addEventListener('load', initAOS, { once: true });
        }
        
        // Enhanced smooth scroll with custom easing
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const targetPosition = target.offsetTop - 80;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add parallax effect on scroll
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollDiff = scrollTop - lastScrollTop;
            
            document.querySelectorAll('[data-parallax]').forEach(element => {
                const speed = element.dataset.parallax || 0.5;
                const yPos = -(scrollTop * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
            
            lastScrollTop = scrollTop;
        }, { passive: true });
        
        // Default lazy loading for images (opt-out via data-no-lazy)
        document.querySelectorAll('img:not([data-no-lazy])').forEach(img => {
            if (!img.hasAttribute('loading')) img.setAttribute('loading', 'lazy');
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.6s ease-in-out';
            
            if (img.complete) {
                img.style.opacity = '1';
            } else {
                img.addEventListener('load', () => {
                    img.style.opacity = '1';
                });
            }
        });

        // Conditionally load particles.js only if needed
        const needsParticles = document.getElementById('particles-js') || document.querySelector('[data-particles]');
        if (needsParticles) {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js';
            s.defer = true;
            s.onload = () => {
                // Initialize with a basic config if a target exists and no config was set
                if (window.particlesJS && document.getElementById('particles-js')) {
                    particlesJS('particles-js', {
                        particles: { number: { value: 40 }, color: { value: '#3b82f6' }, size: { value: 2 } },
                        interactivity: { events: { onhover: { enable: true, mode: 'repulse' } } }
                    });
                }
            };
            document.body.appendChild(s);
        }
    </script>
    
</body>
</html>