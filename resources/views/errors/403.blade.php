@php
    $homeRoute = route('login');
    $user = auth()->user();

    if ($user) {
        if ($user->isPimpinan()) {
            $homeRoute = route('dashboard');
        } else {
            $permissions = [
                'dashboard' => 'dashboard',
                'invoices.index' => 'invoices.index',
                'assessments.index' => 'assessments.index',
                'psychological-assessments.index' => 'psychological-assessments.index',
                'family-mapping.index' => 'family-mapping.index',
                'subjects.index' => 'subjects.index',
                'students.index' => 'students.index',
                'services.index' => 'services.index',
                'school-profile.edit' => 'school-profile.edit',
            ];

            foreach ($permissions as $permission => $route) {
                if ($user->hasPermission($permission)) {
                    $homeRoute = route($route);
                    break;
                }
            }
        }
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Ditolak - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden transform transition-all hover:scale-[1.01] duration-300">
            <div class="p-8 text-center">
                <!-- Icon with pulse animation -->
                <div class="relative mb-8 inline-block">
                    <div class="absolute inset-0 bg-red-100 dark:bg-red-900/30 rounded-full animate-ping opacity-75"></div>
                    <div class="relative h-24 w-24 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center mx-auto">
                        <svg class="h-12 w-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">403</h1>
                <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Akses Ditolak</h2>
                
                <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                    Silakan kembali ke halaman utama atau hubungi administrator.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ $homeRoute }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-lg shadow-red-600/30">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Bottom pattern -->
            <div class="h-2 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500"></div>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
