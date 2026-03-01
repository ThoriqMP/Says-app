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
    if ($user->hasPermission('dashboard') || $user->role === 'student') {
        $mobileMenuItems[] = [
            'route' => $user->role === 'student' ? route('student.dashboard') : route('dashboard'),
            'label' => 'Beranda',
            'active_check' => request()->routeIs('dashboard') || request()->routeIs('student.dashboard'),
            'icon_path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
        ];
    }

    // 2. Invoice / Tagihan
    if ($user->hasPermission('invoices.index') || $user->role === 'student') {
        $mobileMenuItems[] = [
            'route' => $user->role === 'student' ? route('student.invoices') : route('invoices.index'),
            'label' => $user->role === 'student' ? 'Tagihan' : 'Invoice',
            'active_check' => request()->routeIs('invoices*') || request()->routeIs('student.invoices*'),
            'icon_path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        ];
    }

    // 3. Raport (Siswa)
    if ($user->role === 'student') {
        $mobileMenuItems[] = [
            'route' => route('student.reports'),
            'label' => 'Raport',
            'active_check' => request()->routeIs('student.reports*'),
            'icon_path' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
        ];
    }

    // 4. Students (Admin)
    if ($user->hasPermission('students.index')) {
        $mobileMenuItems[] = [
            'route' => route('students.index'),
            'label' => 'Siswa',
            'active_check' => request()->routeIs('students*'),
            'icon_path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
        ];
    }

    // Limit to 2 items for bottom nav
    $bottomNavItems = array_slice($mobileMenuItems, 0, 2);

    // Determine Page Title
    $pageTitle = 'SAYS-APP';
    if (request()->routeIs('dashboard') || request()->routeIs('student.dashboard')) $pageTitle = 'Beranda';
    elseif (request()->routeIs('invoices*') || request()->routeIs('student.invoices*')) $pageTitle = 'Tagihan';
    elseif (request()->routeIs('student.reports*')) $pageTitle = 'Raport Saya';
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
    <!-- Mobile Top Bar -->
    <div class="lg:hidden bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="px-4 h-16 flex items-center justify-between gap-3">
            <a href="{{ $user->role === 'student' ? route('student.dashboard') : route('dashboard') }}" class="flex items-center gap-2">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $pageTitle }}</span>
            </a>

            <div class="flex items-center gap-2">
                <button type="button" @click="toggle()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Nav -->
    <div class="lg:hidden fixed bottom-0 w-full z-40" x-data="{ unread: 0 }" @chat-unread-update.window="unread = $event.detail">
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-50">
            <button @click="$dispatch('toggle-admin-chat')" class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span x-show="unread > 0" class="absolute top-0 right-0 bg-red-500 text-[10px] px-1.5 py-0.5 rounded-full border-2 border-white dark:border-gray-800" x-text="unread"></span>
            </button>
        </div>
        <div class="w-full h-16 bg-white dark:bg-gray-800 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]" style="-webkit-mask-image: radial-gradient(circle at 50% 0, transparent 32px, black 33px); mask-image: radial-gradient(circle at 50% 0, transparent 32px, black 33px);">
            <div class="grid grid-cols-5 h-full items-center px-2">
                @foreach($bottomNavItems as $item)
                    <a href="{{ $item['route'] }}" class="flex flex-col items-center justify-center {{ $item['active_check'] ? 'text-blue-600' : 'text-gray-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon_path'] }}"></path></svg>
                        <span class="text-[10px] mt-0.5 font-medium">{{ $item['label'] }}</span>
                    </a>
                @endforeach
                <div></div> <!-- Center Spacer -->
                <button @click="toggle()" class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="text-[10px] mt-1 font-medium">Tema</span>
                </button>
                <button @click="mobileOpen = true" class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="text-[10px] mt-1 font-medium">Menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
        <div class="h-16 px-6 flex items-center border-b border-gray-200 dark:border-gray-700">
            <a href="{{ $user->role === 'student' ? route('student.dashboard') : route('dashboard') }}" class="flex items-center gap-3">
                <div class="h-9 w-9 bg-blue-600 rounded-lg flex items-center justify-center text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-white">SAYS-APP</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($user->role) }}</div>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @if($user->role === 'student')
                <a href="{{ route('student.dashboard') }}" class="{{ $navLink(route('student.dashboard'), request()->routeIs('student.dashboard')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('student.reports') }}" class="{{ $navLink(route('student.reports'), request()->routeIs('student.reports*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Raport Saya</span>
                </a>
                <a href="{{ route('student.invoices') }}" class="{{ $navLink(route('student.invoices'), request()->routeIs('student.invoices*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Tagihan Saya</span>
                </a>
            @else
                <!-- Admin Links -->
                @if($user->hasPermission('dashboard'))
                <a href="{{ route('dashboard') }}" class="{{ $navLink(route('dashboard'), request()->routeIs('dashboard')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                @endif
                @if($user->hasPermission('invoices.index'))
                <a href="{{ route('invoices.index') }}" class="{{ $navLink(route('invoices.index'), request()->routeIs('invoices.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Invoice Siswa</span>
                </a>
                @endif
                @if($user->hasPermission('assessments.index'))
                <a href="{{ route('assessments.index') }}" class="{{ $navLink(route('assessments.index'), request()->routeIs('assessments.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span>Personal Mapping</span>
                </a>
                @endif
                @if($user->hasPermission('psychological-assessments.index'))
                <a href="{{ route('psychological-assessments.index') }}" class="{{ $navLink(route('psychological-assessments.index'), request()->routeIs('psychological-assessments.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Asesmen Psikologis</span>
                </a>
                @endif
                @if($user->hasPermission('family-mapping.index'))
                <a href="{{ route('family-mapping.index') }}" class="{{ $navLink(route('family-mapping.index'), request()->routeIs('family-mapping.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Family Mapping</span>
                </a>
                @endif
                @if($user->hasPermission('reports.manage'))
                <a href="{{ route('admin.reports.index') }}" class="{{ $navLink(route('admin.reports.index'), request()->routeIs('admin.reports.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Manajemen Raport</span>
                </a>
                @endif
                @if($user->hasPermission('students.index'))
                <a href="{{ route('students.index') }}" class="{{ $navLink(route('students.index'), request()->routeIs('students.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Siswa</span>
                </a>
                @endif
                @if($user->hasPermission('services.index'))
                <a href="{{ route('services.index') }}" class="{{ $navLink(route('services.index'), request()->routeIs('services.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Layanan</span>
                </a>
                @endif
                @if($user->hasPermission('school-profile.edit'))
                <a href="{{ route('school-profile.edit') }}" class="{{ $navLink(route('school-profile.edit'), request()->routeIs('school-profile.*')) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span>Profil Sekolah</span>
                </a>
                @endif
                @if($user->isPimpinan())
                    <div class="pt-4 pb-2 px-3"><div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manajemen</div></div>
                    <a href="{{ route('admin-management.index') }}" class="{{ $navLink(route('admin-management.index'), request()->routeIs('admin-management.*')) }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Kelola Admin</span>
                    </a>
                @endif
            @endif
        </nav>

        <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
            <button type="button" @click="toggle()" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="text-sm font-medium" x-show="!dark">Mode Gelap</span>
                <span class="text-sm font-medium" x-show="dark">Mode Terang</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
            <div class="px-3">
                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition">Keluar</button>
            </form>
        </div>
    </aside>

    <!-- Mobile Menu Drawer -->
    <div x-cloak x-show="mobileOpen" class="lg:hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" @click="mobileOpen = false"></div>
        <div class="absolute bottom-20 right-4 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/5"
             x-show="mobileOpen" x-transition:enter="ease-out duration-300 transform translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">{{ substr($user->name, 0, 1) }}</div>
                    <div class="overflow-hidden">
                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                </div>
            </div>
            <nav class="p-2 space-y-1 max-h-[60vh] overflow-y-auto">
                @if($user->role === 'student')
                    <a href="{{ route('student.dashboard') }}" class="{{ $navLink(route('student.dashboard'), request()->routeIs('student.dashboard')) }}" @click="mobileOpen = false">Dashboard</a>
                    <a href="{{ route('student.reports') }}" class="{{ $navLink(route('student.reports'), request()->routeIs('student.reports*')) }}" @click="mobileOpen = false">Raport Saya</a>
                    <a href="{{ route('student.invoices') }}" class="{{ $navLink(route('student.invoices'), request()->routeIs('student.invoices*')) }}" @click="mobileOpen = false">Tagihan Saya</a>
                @else
                    @if($user->hasPermission('dashboard')) <a href="{{ route('dashboard') }}" class="{{ $navLink(route('dashboard'), request()->routeIs('dashboard')) }}" @click="mobileOpen = false">Dashboard</a> @endif
                    @if($user->hasPermission('invoices.index')) <a href="{{ route('invoices.index') }}" class="{{ $navLink(route('invoices.index'), request()->routeIs('invoices.*')) }}" @click="mobileOpen = false">Invoice</a> @endif
                    @if($user->hasPermission('assessments.index')) <a href="{{ route('assessments.index') }}" class="{{ $navLink(route('assessments.index'), request()->routeIs('assessments.*')) }}" @click="mobileOpen = false">Personal Mapping</a> @endif
                    @if($user->hasPermission('psychological-assessments.index')) <a href="{{ route('psychological-assessments.index') }}" class="{{ $navLink(route('psychological-assessments.index'), request()->routeIs('psychological-assessments.*')) }}" @click="mobileOpen = false">Asesmen Psikologis</a> @endif
                    @if($user->hasPermission('family-mapping.index')) <a href="{{ route('family-mapping.index') }}" class="{{ $navLink(route('family-mapping.index'), request()->routeIs('family-mapping.*')) }}" @click="mobileOpen = false">Family Mapping</a> @endif
                    @if($user->hasPermission('reports.manage')) <a href="{{ route('admin.reports.index') }}" class="{{ $navLink(route('admin.reports.index'), request()->routeIs('admin.reports.*')) }}" @click="mobileOpen = false">Manajemen Raport</a> @endif
                    @if($user->hasPermission('students.index')) <a href="{{ route('students.index') }}" class="{{ $navLink(route('students.index'), request()->routeIs('students.*')) }}" @click="mobileOpen = false">Siswa</a> @endif
                    @if($user->hasPermission('services.index')) <a href="{{ route('services.index') }}" class="{{ $navLink(route('services.index'), request()->routeIs('services.*')) }}" @click="mobileOpen = false">Layanan</a> @endif
                    @if($user->hasPermission('school-profile.edit')) <a href="{{ route('school-profile.edit') }}" class="{{ $navLink(route('school-profile.edit'), request()->routeIs('school-profile.*')) }}" @click="mobileOpen = false">Profil Sekolah</a> @endif
                    @if($user->isPimpinan()) <a href="{{ route('admin-management.index') }}" class="{{ $navLink(route('admin-management.index'), request()->routeIs('admin-management.*')) }}" @click="mobileOpen = false">Kelola Admin</a> @endif
                @endif
                <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-red-600 font-bold hover:bg-red-50 rounded-lg">Keluar</button>
                </form>
            </nav>
        </div>
    </div>
</div>
