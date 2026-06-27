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

    $availableRoles = [];
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin'))       $availableRoles[] = 'admin';
        if (Auth::user()->hasRole('headmaster'))  $availableRoles[] = 'headmaster';
        if (Auth::user()->hasRole('teacher'))     $availableRoles[] = 'teacher';
        if (Auth::user()->hasRole('parent'))      $availableRoles[] = 'parent';
    }
    $activeRole = session('active_role', Auth::check() ? Auth::user()->role : null);
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

                @if(count($availableRoles) > 1)
                <div class="px-6 py-3 border-b border-white/10 sidebar-text shrink-0">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Pilih Area Akses</label>
                    <form action="{{ route('switch-role') }}" method="POST" id="role-switcher-form">
                        @csrf
                        <div class="relative">
                            <select name="role" onchange="this.form.submit()" class="w-full bg-white/10 hover:bg-white/15 border border-white/20 rounded-xl px-3 py-2 text-xs font-black text-white focus:outline-none focus:ring-1 focus:ring-secondary/50 cursor-pointer appearance-none">
                                @foreach($availableRoles as $roleOption)
                                    <option value="{{ $roleOption }}" {{ $activeRole === $roleOption ? 'selected' : '' }} class="text-slate-800 font-bold bg-white">
                                        @if($roleOption === 'admin') Administrator
                                        @elseif($roleOption === 'headmaster') Kepala Sekolah
                                        @elseif($roleOption === 'teacher') Area Guru
                                        @elseif($roleOption === 'parent') Area Orang Tua
                                        @else {{ ucfirst($roleOption) }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-300">
                                <i class="fa-solid fa-chevron-down text-[9px]"></i>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    @if($activeRole !== 'parent')
                    <a href="/dashboard"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                       {{ request()->is('*dashboard') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                        <i class="fa-solid fa-house w-5 text-center text-lg shrink-0"></i>
                        <span class="text-sm sidebar-text">Home</span>
                    </a>
                    @endif

                    @if($activeRole == 'admin')
                        <a href="{{ route('admin.management.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->is('admin/management*') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-grip w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Management</span>
                        </a>
                    @endif

                    @if($activeRole == 'headmaster')
                        <a href="{{ route('headmaster.akademik') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('headmaster.akademik') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-chart-bar w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Akademik</span>
                        </a>

                        <a href="{{ route('headmaster.kehadiran') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('headmaster.kehadiran') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-calendar-check w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Kehadiran</span>
                        </a>

                        <a href="{{ route('headmaster.pengesahan.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('headmaster.pengesahan.*') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-stamp w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Pengesahan Rapor</span>
                        </a>
                    @endif

                    @if($activeRole == 'teacher')
                        <a href="{{ route('teacher.kelas_saya.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('teacher.kelas_saya.index') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-book-open w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Kelas Saya</span>
                        </a>

                        <a href="{{ route('teacher.laporan.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('teacher.laporan.*') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-file-lines w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Laporan</span>
                        </a>

                        @if(auth()->user()->teacher && auth()->user()->teacher->classroomAsHomeroom()->exists())
                        <a href="{{ route('teacher.homeroom.index') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('teacher.homeroom.*') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-chalkboard-user w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Wali Kelas</span>
                        </a>
                        @endif
                    @endif

                    @if($activeRole == 'parent')
                        <a href="{{ route('parent.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('parent.dashboard') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-house w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Home</span>
                        </a>

                        <a href="{{ route('parent.academic') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('parent.academic') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-user-graduate w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Akademik</span>
                        </a>

                        <a href="{{ route('parent.attendance') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('parent.attendance') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-calendar-check w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Kehadiran</span>
                        </a>

                        <a href="{{ route('parent.report') }}"
                        class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition border-l-4
                        {{ request()->routeIs('parent.report') ? 'bg-white/10 text-secondary font-bold border-secondary' : 'text-gray-400 font-medium border-transparent hover:bg-white/10 hover:text-secondary' }}">
                            <i class="fa-solid fa-file-circle-check w-5 text-center text-lg shrink-0"></i>
                            <span class="text-sm sidebar-text">Rapor</span>
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

                @if($activeRole !== 'parent')
                <a href="/dashboard"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->is('*dashboard') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('*dashboard') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-house text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Home</span>
                </a>
                @endif

                @if($activeRole == 'admin')
                <a href="{{ route('admin.management.index') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ (request()->is('admin/*') && !request()->is('admin/dashboard')) ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ (request()->is('admin/*') && !request()->is('admin/dashboard')) ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-grip text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Management</span>
                </a>
                @endif

                @if($activeRole == 'headmaster')
                <a href="{{ route('headmaster.akademik') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('headmaster.akademik') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('headmaster.index') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-chart-bar text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Akademik</span>
                    </a>

                    <a href="{{ route('headmaster.kehadiran') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('headmaster.kehadiran.*') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('headmaster.kehadiran.*') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-calendar-check text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Kehadiran</span>
                    </a>

                    <a href="{{ route('headmaster.pengesahan.index') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('headmaster.pengesahan.index.*') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('headmaster.kehadiran.*') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-stamp text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Rapor</span>
                    </a>
                @endif

                @if($activeRole == 'teacher')
                    <a href="{{ route('teacher.kelas_saya.index') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('teacher.kelas_saya.index') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->is('kelas_saya.index') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-book-open text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Kelas Saya</span>
                    </a>

                    <a href="{{ route('teacher.laporan.index') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('teacher.laporan.*') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('teacher.laporan.*') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-file-lines text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Laporan</span>
                    </a>

                    @if(auth()->user()->teacher && auth()->user()->teacher->classroomAsHomeroom()->exists())
                    <a href="{{ route('teacher.homeroom.index') }}"
                    class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                    {{ request()->routeIs('teacher.homeroom.index') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                        <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('teacher.homeroom.index') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                        <i class="fa-solid fa-chalkboard-user text-lg mb-0.5"></i>
                        <span class="text-[10px] tracking-wide">Wali Kelas</span>
                    </a>
                    @endif
                @endif

                @if($activeRole == 'parent')
                <a href="{{ route('parent.dashboard') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->routeIs('parent.dashboard') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('parent.dashboard') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-house text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Home</span>
                </a>

                <a href="{{ route('parent.academic') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->routeIs('parent.academic') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('parent.academic') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-user-graduate text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Akademik</span>
                </a>

                <a href="{{ route('parent.attendance') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->routeIs('parent.attendance') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('parent.attendance') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-calendar-check text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Kehadiran</span>
                </a>

                <a href="{{ route('parent.report') }}"
                   class="relative flex flex-col items-center justify-center w-16 h-full transition-colors
                   {{ request()->routeIs('parent.report') ? 'text-primary font-bold' : 'text-gray-400 font-medium hover:text-primary' }}">
                    <span class="absolute top-0 left-1.5 right-1.5 h-1 bg-secondary rounded-b-md transition-all duration-300 {{ request()->routeIs('parent.report') ? 'opacity-100 scale-x-100' : 'opacity-0 scale-x-0' }}"></span>
                    <i class="fa-solid fa-file-circle-check text-lg mb-0.5"></i>
                    <span class="text-[10px] tracking-wide">Rapor</span>
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

        @stack('scripts')
    </body>
</html>
