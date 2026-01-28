@php
    $navLink = function (string $href, bool $active): string {
        return $active
            ? 'flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white font-semibold'
            : 'flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition';
    };
@endphp

<div>
    <div class="lg:hidden bg-white dark:bg-gray-800 shadow border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="px-4 h-16 flex items-center justify-between gap-3">
            <button type="button" @click="mobileOpen = true"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">SAYS-APP</span>
            </a>

            <div class="flex items-center gap-2">
                <button type="button" @click="toggle()"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="dark">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>

                <div class="relative">
                    <button type="button" @click="userOpen = !userOpen"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-cloak x-show="userOpen" @click.away="userOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
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
