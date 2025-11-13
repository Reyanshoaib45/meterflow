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

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-animations.css') }}">

    <!-- Global Form Input Styles -->
    <link rel="stylesheet" href="{{ asset('css/form-inputs.css') }}">

    <!-- Custom Table Styles -->
    <link rel="stylesheet" href="{{ asset('css/table-styles.css') }}">

    <script>
        tailwind.config = {
            darkMode: 'class',
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

    <!-- Dark Mode Script -->
    <script>
        (function() {
            // Check for saved theme preference or default to light mode
            const getTheme = () => {
                if (localStorage.getItem('theme')) {
                    return localStorage.getItem('theme');
                }
                // Check system preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
                return 'light';
            };

            // Apply theme on page load
            const theme = getTheme();
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Toggle function
            const toggleDarkMode = () => {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');

                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            };

            // Attach event listeners when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButtons = document.querySelectorAll('#darkModeToggle, #darkModeToggleMobile, #darkModeToggleMobileMenu');
                toggleButtons.forEach(button => {
                    if (button) {
                        button.addEventListener('click', toggleDarkMode);
                    }
                });
            });
        })();
    </script>
</head>
<body class="font-sans antialiased">
    <!-- Page Loader -->
    <div id="page-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-gray-900 transition-opacity duration-300">
        <div class="text-center">
            <div class="loader w-24 h-24">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                    <path d="M10,20 C10,17.24 11.12,14.74 12.93,12.93 L12.93,12.93 C14.74,11.12 17.24,10 20,10 L80,10 C82.76,10 85.26,11.12 87.07,12.93 L87.07,12.93 C88.88,14.74 90,17.24 90,20 L90,80 C90,82.76 88.88,85.26 87.07,87.07 L87.07,87.07 C85.26,88.88 82.76,90 80,90 L20,90 C17.24,90 14.74,88.88 12.93,87.07 L12.93,87.07 C11.12,85.26 10,82.76 10,80Z M68,50 C68,45.02 65.98,40.52 62.72,37.27 L62.72,37.27 C59.47,34.01 54.97,32 50,32 L50,32 C45.02,32 40.52,34.01 37.27,37.27 L37.27,37.27 C34.01,40.52 32,45.02 32,50 L32,50 C32,54.97 34.01,59.47 37.27,62.72 L37.27,62.72 C40.52,65.98 45.02,68 50,68 L50,68 C54.97,68 59.47,65.98 62.72,62.72 L62.72,62.72 C65.98,59.47 68,54.97 68,50Z"></path>
                    <path d="M10,20 C10,17.24 11.12,14.74 12.93,12.93 L12.93,12.93 C14.74,11.12 17.24,10 20,10 L80,10 C82.76,10 85.26,11.12 87.07,12.93 L87.07,12.93 C88.88,14.74 90,17.24 90,20 L90,80 C90,82.76 88.88,85.26 87.07,87.07 L87.07,87.07 C85.26,88.88 82.76,90 80,90 L20,90 C17.24,90 14.74,88.88 12.93,87.07 L12.93,87.07 C11.12,85.26 10,82.76 10,80Z"></path>
                    <path d="M10,37.57 C10,34.92 11.05,32.37 12.92,30.5 L30.5,12.92 C32.37,11.05 34.92,10 37.57,10 L62.42,10 C65.07,10 67.62,11.05 69.49,12.92 L87.07,30.5 C88.94,32.37 90,34.92 90,37.57 L90,62.42 C90,65.07 88.94,67.62 87.07,69.49 L69.49,87.07 C67.62,88.94 65.07,90 62.42,90 L37.57,90 C34.92,90 32.37,88.94 30.5,87.07 L12.92,69.49 C11.05,67.62 10,65.07 10,62.42Z"></path>
                    <path d="M10,50 C10,38.95 14.48,28.95 21.72,21.72 L21.72,21.72 C28.95,14.48 38.95,10 50,10 L50,10 C61.05,10 71.05,14.48 78.28,21.72 L78.28,21.72 C85.52,28.95 90,38.95 90,50 L90,50 C90,61.05 85.52,71.05 78.28,78.28 L78.28,78.28 C71.05,85.52 61.05,90 50,90 L50,90 C38.95,90 28.95,85.52 21.72,78.28 L21.72,78.28 C14.48,71.05 10,61.05 10,50Z"></path>
                </svg>
            </div>
            <p class="mt-4 text-gray-700 dark:text-gray-300 text-sm font-medium">Loading...</p>
        </div>
    </div>

    <script>
        // Enhanced page loader with smooth transition
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 300);
            }

            // Add fade-in animation to content
            document.body.classList.add('page-loaded');
        });

        // Smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add loading state to forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM' && !form.dataset.noLoading) {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML || submitBtn.value;
                    submitBtn.innerHTML = '<span class="loader w-4 h-4 inline-block mr-2"></span> Processing...';
                    submitBtn.setAttribute('data-original-text', originalText);
                }
            }
        });

        // Enhanced form validation feedback
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                this.classList.remove('border-gray-300', 'dark:border-gray-600');

                // Show error message
                let errorMsg = this.parentElement.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message text-red-600 dark:text-red-400 text-sm mt-1';
                    this.parentElement.appendChild(errorMsg);
                }
                errorMsg.textContent = this.validationMessage;

                // Remove error on input
                this.addEventListener('input', function() {
                    this.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                    this.classList.add('border-gray-300', 'dark:border-gray-600');
                    if (errorMsg) errorMsg.remove();
                }, { once: true });
            });

            // Add success state on valid input
            field.addEventListener('blur', function() {
                if (this.validity.valid && this.value) {
                    this.classList.add('border-green-500');
                    this.classList.remove('border-gray-300', 'dark:border-gray-600', 'border-red-500');
                }
            });
        });

        // Keyboard navigation enhancement
        document.addEventListener('keydown', function(e) {
            // Escape key to close modals/notifications
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal, [role="dialog"]');
                modals.forEach(modal => {
                    if (modal.style.display !== 'none') {
                        modal.style.display = 'none';
                    }
                });
            }
        });

        // Add focus-visible polyfill for better keyboard navigation
        if (!CSS.supports('selector(:focus-visible)')) {
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });
        }
    </script>

     @include('layouts.navigation')
    <!-- Background Pattern -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC40Ij48Y2lyY2xlIGN4PSIzMCIgY3k9IjMwIiByPSIyIi8+PC9nPjwvZz48L3N2Zz4=')] opacity-20 dark:opacity-10"></div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>

    <!-- Enhanced Flash Messages -->
    @if (session('status'))
        <div class="fixed top-4 right-4 z-50 max-w-sm transform translate-x-full opacity-0 transition-all duration-300 ease-out" id="flash-success">
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-xl backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('status') }}</span>
                    </div>
                    <button onclick="closeFlashMessage('flash-success')" class="ml-4 text-green-600 dark:text-green-400 hover:opacity-70 transition-opacity">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const flash = document.getElementById('flash-success');
                if (flash) {
                    flash.classList.remove('translate-x-full', 'opacity-0');
                    flash.classList.add('translate-x-0', 'opacity-100');
                    setTimeout(() => {
                        closeFlashMessage('flash-success');
                    }, 5000);
                }
            }, 100);
        </script>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 max-w-sm transform translate-x-full opacity-0 transition-all duration-300 ease-out" id="flash-error">
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-xl backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="closeFlashMessage('flash-error')" class="ml-4 text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const flash = document.getElementById('flash-error');
                if (flash) {
                    flash.classList.remove('translate-x-full', 'opacity-0');
                    flash.classList.add('translate-x-0', 'opacity-100');
                    setTimeout(() => {
                        closeFlashMessage('flash-error');
                    }, 5000);
                }
            }, 100);
        </script>
    @endif

    <script>
        function closeFlashMessage(id) {
            const flash = document.getElementById(id);
            if (flash) {
                flash.classList.remove('translate-x-0', 'opacity-100');
                flash.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    flash.remove();
                }, 300);
            }
        }
    </script>

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
