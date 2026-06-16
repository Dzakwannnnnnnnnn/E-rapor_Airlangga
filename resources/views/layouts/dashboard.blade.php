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

                    <a href="/profile"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                       {{ request()->is('*profile') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                        <i class="fa-solid fa-user w-5 text-center text-lg shrink-0"></i>
                        <span class="text-sm sidebar-text">Profile</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-white/10 bg-primary-light/10 shrink-0 sidebar-profile-block transition-all duration-300">
                    <div class="flex items-center gap-3 px-2 mb-4 min-w-0 sidebar-user-info">
                        <div class="w-10 h-10 rounded-full bg-white text-primary flex items-center justify-center font-bold shrink-0 shadow-inner">
                            {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0 sidebar-text">
                            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'Nama Petugas' }}</p>
                            <p class="text-xs text-secondary truncate">{{ Auth::user()->role ?? 'Role' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-danger/80 hover:bg-danger text-white rounded font-bold transition text-sm shadow-sm">
                            <i class="fa-solid fa-arrow-right-from-bracket shrink-0"></i>
                            <span class="sidebar-text">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 h-screen overflow-y-auto bg-bg relative pb-20 md:pb-0 sidebar-transition">

                <div class="md:hidden flex items-center justify-between bg-primary px-4 h-16 border-b border-white/10 sticky top-0 z-30 shadow-md">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded flex items-center justify-center bg-white p-0.5 shadow-sm">
                            <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="Logo">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-white text-sm leading-none mb-0.5">E Rapor</span>
                            <span class="text-[10px] text-secondary font-medium">SMKTI Airlangga Samarinda</span>
                        </div>
                    </div>

                    <div class="w-8 h-8 rounded-full bg-white text-primary flex items-center justify-center text-xs font-bold shadow-sm">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>

                @if(session('error'))
                    <div class="mt-4 mx-4 md:mx-8 flex items-center gap-3 p-4 rounded-lg bg-primary border-l-4 border-secondary shadow-md relative overflow-hidden">
                        <div class="absolute inset-0 bg-secondary opacity-5 pointer-events-none"></div>
                        <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center shrink-0 z-10">
                            <i class="fa-solid fa-triangle-exclamation text-secondary text-lg"></i>
                        </div>
                        <div class="flex-1 z-10">
                            <p class="text-white text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-white/60 hover:text-secondary transition-colors z-10 p-2">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mt-4 mx-4 md:mx-8 flex items-center gap-3 p-4 rounded-lg bg-primary border-l-4 border-secondary shadow-md relative overflow-hidden">
                        <div class="absolute inset-0 bg-secondary opacity-5 pointer-events-none"></div>
                        <div class="w-10 h-10 rounded-full bg-secondary/20 flex items-center justify-center shrink-0 z-10">
                            <i class="fa-solid fa-circle-check text-secondary text-lg"></i>
                        </div>
                        <div class="flex-1 z-10">
                            <h4 class="text-secondary font-bold text-xs uppercase tracking-wider mb-0.5">Berhasil</h4>
                            <p class="text-white text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-white/60 hover:text-secondary transition-colors z-10 p-2">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                @endif

                <div class="p-4 md:p-8">
                    @yield('content')
                </div>
            </main>

            @if(!View::hasSection('hide_bottom_nav'))
            <div class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-100 flex justify-around items-center z-50 px-2 shadow-[0_-4px_14px_rgba(0,0,0,0.08)]">

                <a href="/dashboard"
                   class="flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*dashboard') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <i class="fa-solid fa-house text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Home</span>
                </a>

                <a href="/profile"
                   class="flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*profile') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <i class="fa-solid fa-user text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Profile</span>
                </a>
            </div>
            @endif

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.getElementById('sidebar-toggle');
                const toggleIcon = document.getElementById('sidebar-toggle-icon');

                if (toggleBtn && sidebar) {
                    // Sinkronisasi ikon panah awal berdasarkan class terpasang
                    if (sidebar.classList.contains('sidebar-collapsed')) {
                        toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                    }

                    // Event Listener Klik
                    toggleBtn.addEventListener('click', () => {
                        const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', isCollapsed);

                        // Transformasi arah ikon panah secara dinamis
                        if (isCollapsed) {
                            toggleIcon.classList.replace('fa-chevron-left', 'fa-chevron-right');
                        } else {
                            toggleIcon.classList.replace('fa-chevron-right', 'fa-chevron-left');
                        }
                    });
                }
            });
        </script>
    </body>
</html>
