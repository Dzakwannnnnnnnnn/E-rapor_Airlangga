@extends('layouts.dashboard')

@section('title', 'Profil Saya - e-Rapor')

@section('content')
<!-- Breakout container for full width hero banner -->
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <!-- Hero Banner (Full width) -->
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">

        <!-- Background Decorations (referensi welcome.blade.php) -->
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <!-- Profile Banner Content (Centered to match content grid) -->
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <!-- Avatar with camera icon overlay -->
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-md border border-white cursor-pointer hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                @if($user->hasVerifiedEmail())
                    <div class="inline-flex items-center gap-1.5 bg-success text-[10px] text-white font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                        <i class="fa-solid fa-circle-check text-[9px]"></i> Terverifikasi
                    </div>
                @else
                    <div class="inline-flex items-center gap-1.5 bg-amber-500 text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Belum Verifikasi
                    </div>
                @endif
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">{{ $user->name }}</h3>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-base text-secondary opacity-95"></i>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-base text-secondary opacity-95"></i>
                        <span>Akses: <strong class="text-white">{{ ucfirst($user->role) }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slanted Bottom Lines (referensi welcome.blade.php) -->
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10"
             style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);">
        </div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10"
             style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);">
        </div>
    </div>
</div>

<!-- Grid/Page Content container below the hero -->
<div class="max-w-6xl mx-auto mt-6 space-y-6">

    <!-- Stats Card Grid (Overlapping the Banner) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-3xl mx-auto -mt-16 mb-8 relative z-20 px-4">
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-lock text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Role Akses</span>
                <span class="text-base font-black text-slate-800 uppercase leading-none mt-1 block">{{ $user->role }}</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Akun</span>
                <span class="text-base font-black text-slate-800 uppercase leading-none mt-1 block">AKTIF</span>
            </div>
        </div>
    </div>

    <!-- Email Verification Alert Banner -->
    @if(!$user->hasVerifiedEmail())
        <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 rounded-2xl p-4 shadow-sm flex items-center gap-4 max-w-3xl mx-auto select-none mt-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-envelope-circle-check text-lg"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-extrabold text-xs uppercase tracking-wide">Email Belum Terverifikasi</h5>
                <p class="text-xs text-amber-750 mt-0.5 leading-normal">Silakan verifikasi email Anda di menu <a href="{{ route('profile.edit-details') }}" class="underline font-black text-amber-950 hover:text-black">Edit Profil</a> agar akun Anda sepenuhnya aman dan Anda mendapatkan akses penuh.</p>
            </div>
        </div>
    @endif

    <!-- Responsive Settings Section Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-4 px-4 md:px-0">

        <!-- Left Side: Akun Saya (Occupies 2 columns on desktop) -->
        <div class="lg:col-span-2 space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Akun Saya</h4>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden divide-y divide-slate-100">

                <!-- Edit Profil -->
                <a href="{{ route('profile.edit-details') }}"
                   class="w-full flex items-center justify-between p-4 md:p-5 hover:bg-slate-50/50 transition-colors text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary/10 group-hover:text-primary">
                            <i class="fa-solid fa-user text-base"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800 text-sm">Edit Profil</h5>
                            <p class="text-xs text-slate-400">Ubah nama lengkap dan alamat email Anda</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>

                <!-- Ubah Password -->
                <a href="{{ route('profile.change-password') }}"
                   class="w-full flex items-center justify-between p-4 md:p-5 hover:bg-slate-50/50 transition-colors text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary/10 group-hover:text-primary">
                            <i class="fa-solid fa-key text-base"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800 text-sm">Ubah Password</h5>
                            <p class="text-xs text-slate-400">Pastikan akun Anda menggunakan kata sandi yang aman</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </a>

            </div>
        </div>

        <!-- Right Side: Bantuan & Lainnya (Occupies 1 column on desktop) -->
        <div class="space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Bantuan & Lainnya</h4>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden divide-y divide-slate-100">

                <!-- Hapus Akun Modal Trigger -->
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="w-full flex items-center justify-between p-4 md:p-5 hover:bg-red-50/30 transition-colors text-left group bg-white">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0 group-hover:bg-red-100">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-red-600 text-sm">Hapus Akun</h5>
                            <p class="text-xs text-red-400">Hapus data akun ini secara permanen</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-red-300 text-xs"></i>
                </button>

                <!-- Keluar Form Trigger -->
                <form method="POST" action="{{ route('logout') }}" id="profile-logout-form" class="hidden">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();"
                        class="w-full flex items-center justify-between p-4 md:p-5 hover:bg-slate-50/50 transition-colors text-left group bg-white">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 group-hover:bg-slate-200">
                            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800 text-sm">Keluar</h5>
                            <p class="text-xs text-slate-400">Keluar dari sesi dashboard saat ini</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 text-xs"></i>
                </button>

            </div>
        </div>

    </div>
</div>

<!-- Modal Hapus Akun -->
@include('profile.partials.delete-user-form')

@endsection
