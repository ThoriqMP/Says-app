@php
    $navLink = function (string $href, bool $active): string {
        return $active
            ? 'flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold'
            : 'flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition';
    };

    // Prepare Mobile Bottom Nav Items (Top 2 Priority)
    $mobileMenuItems = [];
    $user = auth()->user();

    // 1. Dashboard
    if ($user->hasPermission('dashboard')) {
        $mobileMenuItems[] = [
            'route' => route('dashboard'),
            'label' => 'Beranda',
            'active_check' => request()->routeIs('dashboard'),
            'icon_path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
        ];
    }

    // 2. Invoice
    if ($user->hasPermission('invoices.index')) {
        $mobileMenuItems[] = [
            'route' => route('invoices.index'),
            'label' => 'Invoice',
            'active_check' => request()->routeIs('invoices*'),
            'icon_path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        ];
    }

    // 3. Students
    if ($user->hasPermission('students.index')) {
        $mobileMenuItems[] = [
            'route' => route('students.index'),
            'label' => 'Siswa',
            'active_check' => request()->routeIs('students*'),
            'icon_path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
        ];
    }

    // 4. Personal Mapping
    if ($user->hasPermission('assessments.index')) {
        $mobileMenuItems[] = [
            'route' => route('assessments.index'),
            'label' => 'Mapping',
            'active_check' => request()->routeIs('assessments*'),
            'icon_path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'
        ];
    }
    
    // 5. Psychological Assessments
    if ($user->hasPermission('psychological-assessments.index')) {
        $mobileMenuItems[] = [
            'route' => route('psychological-assessments.index'),
            'label' => 'Psikologis',
            'active_check' => request()->routeIs('psychological-assessments*'),
            'icon_path' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
        ];
    }

    // Limit to 2 items
    $bottomNavItems = array_slice($mobileMenuItems, 0, 2);

    // Determine Page Title for Mobile Top Bar
    $pageTitle = 'SAYS-APP';
    if (request()->routeIs('dashboard')) $pageTitle = 'Beranda';
    elseif (request()->routeIs('invoices*')) $pageTitle = 'Invoice';
    elseif (request()->routeIs('students*')) $pageTitle = 'Data Siswa';
    elseif (request()->routeIs('assessments*')) $pageTitle = 'Personal Mapping';
    elseif (request()->routeIs('psychological-assessments*')) $pageTitle = 'Asesmen Psikologis';
    elseif (request()->routeIs('family-mapping*')) $pageTitle = 'Family Mapping';
    elseif (request()->routeIs('subjects*')) $pageTitle = 'Daftar Subjek';
    elseif (request()->routeIs('services*')) $pageTitle = 'Layanan';
    elseif (request()->routeIs('school-profile*')) $pageTitle = 'Profil Sekolah';
    elseif (request()->routeIs('admin-management*')) $pageTitle = 'Kelola Admin';
@endphp

<div>
    <div class="lg:hidden bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="px-4 h-16 flex items-center justify-between gap-3">
            <!-- Mobile Menu Button Hidden (Replaced by Bottom Nav) -->
            <!-- <button type="button" @click="mobileOpen = true" ...> ... </button> -->
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pageTitle }}</span>
            </a>

            <!-- Right Side (User/Theme) - Optional, can keep or move to bottom -->
            <div class="flex items-center gap-2">
                <!-- Theme Toggle -->
                <button type="button" @click="toggle()"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="lg:hidden fixed bottom-0 w-full z-40" x-data="{ unread: 0 }" @chat-unread-update.window="unread = $event.detail">
        <!-- Floating Chat Button (Positioned in Notch) -->
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-50">
            <button @click="$dispatch('toggle-admin-chat')" 
                    class="flex items-center justify-center w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition active:scale-95">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <!-- Unread Badge -->
                <span x-show="unread > 0" 
                      class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-gray-800"
                      x-text="unread">
                </span>
            </button>
        </div>

        <!-- Navigation Bar (Masked) -->
        <div class="w-full h-16 bg-white dark:bg-gray-800 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]"
             style="-webkit-mask-image: radial-gradient(circle at 50% 0, transparent 32px, black 33px); mask-image: radial-gradient(circle at 50% 0, transparent 32px, black 33px);">
            <div class="grid grid-cols-5 h-full items-center px-2">
                <!-- Dynamic Menu Items -->
                @foreach($bottomNavItems as $item)
                    <a href="{{ $item['route'] }}" class="flex flex-col items-center justify-center w-full h-full {{ $item['active_check'] ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                        <div class="p-1 rounded-xl transition-colors duration-200 {{ $item['active_check'] ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon_path'] }}"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] mt-0.5 font-medium">{{ $item['label'] }}</span>
                    </a>
                @endforeach

                <!-- Fill empty slots if less than 2 items -->
                @for($i = count($bottomNavItems); $i < 2; $i++)
                    <div></div>
                @endfor

                <!-- Center Spacer (Empty because button is absolute) -->
                <div class="pointer-events-none"></div>

                <!-- Theme Toggle -->
                <button @click="toggle()" class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-[10px] mt-1 font-medium">Tema</span>
                </button>

                <!-- Menu (Triggers Drawer) -->
                <button @click="mobileOpen = true" class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="text-[10px] mt-1 font-medium">Menu</span>
                </button>
            </div>
        </div>
    </div>

    <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="h-16 px-6 flex items-center border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="h-9 w-9 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">SAYS-APP</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->hasPermission('dashboard'))
            <a href="{{ route('dashboard') }}" class="{{ $navLink(route('dashboard'), request()->routeIs('dashboard')) }}">
                <span>Dashboard</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('invoices.index'))
            <a href="{{ route('invoices.index') }}" class="{{ $navLink(route('invoices.index'), request()->routeIs('invoices.*')) }}">
                <span>Invoice</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('assessments.index'))
            <a href="{{ route('assessments.index') }}" class="{{ $navLink(route('assessments.index'), request()->routeIs('assessments.*')) }}">
                <span>Personal Mapping</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('psychological-assessments.index'))
            <a href="{{ route('psychological-assessments.index') }}" class="{{ $navLink(route('psychological-assessments.index'), request()->routeIs('psychological-assessments.*')) }}">
                <span>Asesmen Psikologis</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('family-mapping.index'))
                <a href="{{ route('family-mapping.index') }}" class="{{ $navLink(route('family-mapping.index'), request()->routeIs('family-mapping.*')) }}">
                    <span>Family Mapping</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('subjects.index'))
            <a href="{{ route('subjects.index') }}" class="{{ $navLink(route('subjects.index'), request()->routeIs('subjects.*')) }}">
                <span>Subjek</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('students.index'))
            <a href="{{ route('students.index') }}" class="{{ $navLink(route('students.index'), request()->routeIs('students.*')) }}">
                <span>Siswa</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('services.index'))
            <a href="{{ route('services.index') }}" class="{{ $navLink(route('services.index'), request()->routeIs('services.*')) }}">
                <span>Layanan</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('school-profile.edit'))
            <a href="{{ route('school-profile.edit') }}" class="{{ $navLink(route('school-profile.edit'), request()->routeIs('school-profile.*')) }}">
                <span>Profil Sekolah</span>
            </a>
            @endif

            @if(auth()->user()->isPimpinan())
                <div class="pt-4 pb-2">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</div>
                    <a href="{{ route('admin-management.index') }}" class="{{ $navLink(route('admin-management.index'), request()->routeIs('admin-management.*')) }}">
                        <span>Kelola Admin</span>
                    </a>
                </div>
            @endif
        </nav>

        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
            <button type="button" @click="toggle()"
                    class="w-full inline-flex items-center justify-between px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <span class="text-sm font-medium" x-show="!dark">Mode Gelap</span>
                <span class="text-sm font-medium" x-show="dark">Mode Terang</span>
                <span class="inline-flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </span>
            </button>

            <div class="px-3">
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition font-semibold">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div x-cloak x-show="mobileOpen" class="lg:hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
        <div class="absolute inset-0 bg-black/50" @click="mobileOpen = false"></div>
        <div class="absolute inset-y-0 left-0 w-72 max-w-[80vw] bg-white dark:bg-gray-800 shadow-xl flex flex-col">
            <div class="h-16 px-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2" @click="mobileOpen = false">
                    <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">SAYS-APP</span>
                </a>
                <button type="button" @click="mobileOpen = false"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->hasPermission('dashboard'))
                <a href="{{ route('dashboard') }}" class="{{ $navLink(route('dashboard'), request()->routeIs('dashboard')) }}" @click="mobileOpen = false">
                    <span>Dashboard</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('invoices.index'))
                <a href="{{ route('invoices.index') }}" class="{{ $navLink(route('invoices.index'), request()->routeIs('invoices.*')) }}" @click="mobileOpen = false">
                    <span>Invoice</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('assessments.index'))
                <a href="{{ route('assessments.index') }}" class="{{ $navLink(route('assessments.index'), request()->routeIs('assessments.*')) }}" @click="mobileOpen = false">
                    <span>Personal Mapping</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('psychological-assessments.index'))
                <a href="{{ route('psychological-assessments.index') }}" class="{{ $navLink(route('psychological-assessments.index'), request()->routeIs('psychological-assessments.*')) }}" @click="mobileOpen = false">
                    <span>Asesmen Psikologis</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('family-mapping.index'))
                <a href="{{ route('family-mapping.index') }}" class="{{ $navLink(route('family-mapping.index'), request()->routeIs('family-mapping.*')) }}" @click="mobileOpen = false">
                    <span>Family Mapping</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('subjects.index'))
                <a href="{{ route('subjects.index') }}" class="{{ $navLink(route('subjects.index'), request()->routeIs('subjects.*')) }}" @click="mobileOpen = false">
                    <span>Subjek</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('students.index'))
                <a href="{{ route('students.index') }}" class="{{ $navLink(route('students.index'), request()->routeIs('students.*')) }}" @click="mobileOpen = false">
                    <span>Siswa</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('services.index'))
                <a href="{{ route('services.index') }}" class="{{ $navLink(route('services.index'), request()->routeIs('services.*')) }}" @click="mobileOpen = false">
                    <span>Layanan</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('school-profile.edit'))
                <a href="{{ route('school-profile.edit') }}" class="{{ $navLink(route('school-profile.edit'), request()->routeIs('school-profile.*')) }}" @click="mobileOpen = false">
                    <span>Profil Sekolah</span>
                </a>
                @endif

                @if(auth()->user()->isPimpinan())
                    <div class="pt-4 pb-2">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</div>
                        <a href="{{ route('admin-management.index') }}" class="{{ $navLink(route('admin-management.index'), request()->routeIs('admin-management.*')) }}" @click="mobileOpen = false">
                            <span>Kelola Admin</span>
                        </a>
                    </div>
                @endif
            </nav>

            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                <button type="button" @click="toggle()"
                        class="w-full inline-flex items-center justify-between px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="text-sm font-medium" x-show="!dark">Mode Gelap</span>
                    <span class="text-sm font-medium" x-show="dark">Mode Terang</span>
                    <span class="inline-flex items-center justify-center">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </span>
                </button>

                <div class="px-3">
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition font-semibold">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
