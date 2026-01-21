<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Invoice Management System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <script>
        (() => {
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialDark = localStorage.theme === 'dark' || (!('theme' in localStorage) && prefersDark);
            document.documentElement.classList.toggle('dark', initialDark);
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="appShell">
    <div class="min-h-screen">
        @if(auth()->check())
            <div class="lg:flex">
                @include('layouts.navigation')

                <div class="flex-1 lg:pl-64">
                    @if(isset($header))
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main>
                        @yield('content')
                    </main>
                </div>
            </div>
        @endif
        
        <!-- Page Content -->
        @if(!auth()->check())
            <main>
                @yield('content')
            </main>
        @endif
    </div>
    
    @stack('scripts')
</body>
</html>
