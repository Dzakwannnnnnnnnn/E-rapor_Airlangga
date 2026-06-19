@php
    $initials = '';
    if (Auth::check()) {
        $nameParts = explode(' ', Auth::user()->name);
        $initials = strtoupper(substr($nameParts[0], 0, 1));
        if (count($nameParts) > 1) {
            $initials .= strtoupper(substr($nameParts[1], 0, 1));
        } else {
            $initials .= strtoupper(substr($nameParts[0], 1, 1));
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @media (min-width: 768px) {
                /* Aturan Transisi Lebar Sidebar & Konten Utama */
                .sidebar-transition {
                    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                /* Override Struktur saat Sidebar Mengecil */
                aside.sidebar-collapsed {
                    width: 5rem !important; /* w-20 */
                }
                .sidebar-collapsed .sidebar-text {
                    display: none !important;
                }
                .sidebar-collapsed .sidebar-header-inside {
                    justify-content: center !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
                .sidebar-collapsed .sidebar-header-inside .mr-3 {
                    margin-right: 0 !important;
                }
                .sidebar-collapsed .sidebar-link {
                    justify-content: center !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                    gap: 0 !important;
                }
                .sidebar-collapsed .sidebar-profile-block {
                    padding-left: 0.5rem !important;
                    padding-right: 0.5rem !important;
                }
                .sidebar-collapsed .sidebar-user-info {
                    justify-content: center !important;
                    padding: 0 !important;
                    margin-bottom: 1rem !important;
                }
                .sidebar-collapsed .sidebar-logout-btn {
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                    justify-content: center !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">

        <div class="flex h-screen w-full flex-col md:flex-row overflow-hidden">

            <aside id="sidebar" class="hidden md:flex w-64 bg-primary h-screen flex-col shadow-2xl shrink-0 sidebar-transition relative z-40">

                <script>
                    if (localStorage.getItem('sidebar-collapsed') === 'true') {
                        document.getElementById('sidebar').classList.add('sidebar-collapsed');
                    }
                </script>

                <button id="sidebar-toggle" class="hidden md:flex absolute -right-3 top-7 w-6 h-6 rounded-full bg-white text-primary flex items-center justify-center shadow-lg z-50 hover:scale-110 active:scale-95 transition-transform border border-gray-100">
                    <i id="sidebar-toggle-icon" class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>

                <div class="h-20 flex items-center px-6 border-b border-white/10 shrink-0 sidebar-header-inside transition-all duration-300">
                    <div class="w-9 h-9 flex items-center justify-center text-primary font-black text-lg mr-3 shadow-sm shrink-0">
                        <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="" class="rounded-md">
                    </div>
                    <div class="flex flex-col sidebar-text">
                        <span class="text-white font-bold text-sm tracking-wide uppercase leading-tight truncate">E Rapor</span>
                        <span class="text-secondary text-xs font-semibold truncate">SMKTI Airlangga Samarinda</span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <a href="/dashboard"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                       {{ request()->is('*dashboard') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                        <i class="fa-solid fa-house w-5 text-center text-lg shrink-0"></i>
                        <span class="text-sm sidebar-text">Home</span>
                    </a>

                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('admin.management.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->is('admin/management*') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-grip w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Management</span>
                        </a>


                        <a href="/dashboard"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->is('*dashboards') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-file-lines w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Laporan</span>
                        </a>
                    @endif

                    @if(auth()->user()->role == 'teacher')
                    <a href="{{ route('teacher.kelas_saya.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('teacher.kelas_saya.index') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-book-open w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Kelas Saya</span>
                        </a>


                        <a href="/dashboard"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->is('*dashboards') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-file-lines w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Laporan</span>
                        </a>
                    @endif

                    <a href="/profile"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                       {{ request()->is('*profile') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                        @if(Auth::user()->avatar_url ?? false)
                            <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="w-5 h-5 rounded-full object-cover shrink-0">
                        @else
                            <div class="w-5 h-5 rounded-full bg-secondary text-primary font-black text-[8px] flex items-center justify-center shrink-0 border border-white/10">
                                {{ $initials }}
                            </div>
                        @endif
                        <span class="text-sm sidebar-text">Profile</span>
                    </a>
                </nav>
            </aside>

            <main class="flex-1 h-screen overflow-y-auto bg-bg relative pb-20 md:pb-0 sidebar-transition">

                <x-mobile-top-bar :back-url="View::getSection('back_url')" :title="trim(str_replace(' - e-Rapor', '', View::getSection('title')))" />

                <div class="p-4 md:p-8">
                    @yield('content')
                </div>
            </main>

            @if(View::hasSection('bottom_bar'))
                @yield('bottom_bar')
            @elseif(!View::hasSection('hide_bottom_nav'))
            <div class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-100 flex justify-around items-center z-50 px-2 shadow-[0_-4px_14px_rgba(0,0,0,0.08)]">

                <a href="/dashboard"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*dashboard') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('*dashboard') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-house text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Home</span>
                </a>

                @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.management.index') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ (request()->is('admin/*') && !request()->is('admin/dashboard')) ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ (request()->is('admin/*') && !request()->is('admin/dashboard')) ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-grip text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Management</span>
                </a>

                <a href="/dashboard"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*dashboards') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('*dashboards') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-file-lines text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Laporan</span>
                </a>

                @endif

                @if(auth()->user()->role == 'teacher')
                <a href="{{ route('teacher.kelas_saya.index') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->routeIs('teacher.kelas_saya.index') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('kelas_saya.index') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-book-open text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Kelas Saya</span>
                </a>

                <a href="/dashboard"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*dashboards') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('*dashboards') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-file-lines text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Laporan</span>
                </a>
                @endif

                <a href="/profile"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*profile') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('*profile') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    @if(Auth::user()->avatar_url ?? false)
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile"
                             class="w-5 h-5 rounded-full object-cover shrink-0 mb-0.5 {{ request()->is('*profile') ? 'ring-2 ring-primary' : '' }}">
                    @else
                        <div class="w-5 h-5 rounded-full bg-secondary text-primary font-black text-[8px] flex items-center justify-center shrink-0 mb-0.5 {{ request()->is('*profile') ? 'ring-2 ring-primary' : '' }}">
                            {{ $initials }}
                        </div>
                    @endif
                    <span class="text-[10px] tracking-wide">Profile</span>
                </a>
            </div>
            @endif

        </div>

        <x-toast />
        <x-confirm-modal />

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('sidebar-toggle');
                const toggleIcon = document.getElementById('sidebar-toggle-icon');

                if (toggleBtn && sidebar) {
                    if (sidebar.classList.contains('sidebar-collapsed')) {
                        toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                    }

                    toggleBtn.addEventListener('click', () => {
                        const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', isCollapsed);

                        if (isCollapsed) {
                            toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                        } else {
                            toggleIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                        }
                    });
                }
            });
        </script>

        <form id="global-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </body>
</html>
