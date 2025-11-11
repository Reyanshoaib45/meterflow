<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .spin-slow {
            animation: spin-slow 4s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="text-center">
            <!-- Loading Animation -->
            @if(isset($settings) && $settings['show_maintenance_below_loading'] == '1')
            <div class="mb-8">
                <div class="relative inline-block">
                    <div class="w-32 h-32 border-8 border-blue-200 border-t-blue-600 rounded-full spin-slow"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <i class="fas fa-bolt text-4xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Icon -->
            <div class="float-animation mb-6">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-orange-400 to-red-500 rounded-full shadow-2xl">
                    <i class="fas fa-tools text-6xl text-white"></i>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                We'll Be Right Back!
            </h1>

            <!-- Message -->
            <p class="text-xl md:text-2xl text-gray-600 mb-8">
                Our website is currently undergoing scheduled maintenance.
            </p>

            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-200">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                    <p class="text-lg font-semibold text-gray-800">Maintenance in Progress</p>
                </div>
                
                <p class="text-gray-600 mb-6">
                    We're making improvements to serve you better. This won't take long!
                </p>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="shimmer h-full rounded-full"></div>
                </div>

                <!-- Icons Grid -->
                <div class="grid grid-cols-3 gap-6 mt-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-server text-2xl text-blue-600"></i>
                        </div>
                        <p class="text-sm text-gray-600">Updating Servers</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-database text-2xl text-green-600"></i>
                        </div>
                        <p class="text-sm text-gray-600">Optimizing Database</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
                        </div>
                        <p class="text-sm text-gray-600">Enhancing Security</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg">
                <p class="text-sm mb-2">Need immediate assistance?</p>
                <p class="text-lg font-semibold">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact: support@mepco.com
                </p>
            </div>

            <!-- Footer -->
            <p class="text-gray-500 text-sm mt-8">
                <i class="fas fa-clock mr-1"></i>
                Expected completion: Soon | Thank you for your patience
            </p>
        </div>
    </div>
</body>
</html>
