<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Kadaluarsa - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transform transition-all hover:scale-[1.01] duration-300">
            <div class="p-8 text-center">
                <!-- Icon with pulse animation -->
                <div class="relative mb-8 inline-block">
                    <div class="absolute inset-0 bg-yellow-100 dark:bg-yellow-900/30 rounded-full animate-ping opacity-75"></div>
                    <div class="relative h-24 w-24 bg-yellow-100 dark:bg-yellow-900/50 rounded-full flex items-center justify-center mx-auto">
                        <svg class="h-12 w-12 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">419</h1>
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Halaman Kadaluarsa</h2>
                
                <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                    Sesi Anda telah berakhir atau token keamanan tidak valid.<br>
                    Mohon muat ulang halaman dan coba lagi.
                </p>
                
                <div class="flex justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-lg shadow-blue-600/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login Kembali
                    </a>
                </div>
            </div>
            
            <!-- Bottom pattern -->
            <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
