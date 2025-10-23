<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Meter Flow Nation') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/mfn-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/mfn-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/mfn-logo.png') }}">

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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Enhanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-border {
            0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        /* Animation Classes */
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-fade-in-down {
            animation: fadeInDown 0.6s ease-out forwards;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out forwards;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-pulse-border {
            animation: pulse-border 2s ease-in-out infinite;
        }
        
        /* Hover Effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Loading Shimmer */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 44px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
            color: #374151;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 8px;
        }
        
        .select2-container--default .select2-selection--multiple {
            min-height: 44px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.25rem 0.5rem;
        }
        
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #6366f1;
            border: none;
            color: white;
            border-radius: 0.375rem;
            padding: 0.25rem 0.5rem;
            margin: 0.125rem;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 0.375rem;
            font-weight: bold;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fca5a5;
        }
        
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1;
            color: white;
        }
        
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e0e7ff;
            color: #4338ca;
        }
    </style>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Global Select2 Initialization -->
    <script>
        $(document).ready(function() {
            // Initialize all select elements with select2
            $('select:not(.no-select2)').select2({
                theme: 'default',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true
            });
            
            // Initialize multi-select with special styling
            $('select[multiple]').select2({
                theme: 'default',
                width: '100%',
                placeholder: 'Select multiple options',
                allowClear: true,
                closeOnSelect: false
            });
        });
    </script>
    
     <!-- Script for auto-hiding flash messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                const flashMessages = document.querySelectorAll('[class*="fixed top-4 right-4"]');
                flashMessages.forEach(message => {
                    message.style.opacity = '0';
                    message.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => message.remove(), 500);
                });
            }, 5000);
        });
    </script>
    
    <!-- Infinite Scroll Script -->
    <script src="{{ asset('js/infinite-scroll.js') }}"></script>
    
     @section('script')

    @show
</body>
</html>