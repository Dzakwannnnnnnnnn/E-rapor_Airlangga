@extends('layouts.dashboard')

@section('title', 'Edit Profil - e-Rapor')
@section('back_url', route('profile.edit'))
@section('hide_bottom_nav', true)

@section('content')
<!-- Breakout container for full width hero banner -->
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <!-- Hero Banner (Full width) -->
    <div class="bg-primary text-white pt-10 pb-16 px-6 md:px-8 relative overflow-hidden select-none">

        <!-- Background Decorations (referensi welcome.blade.php) -->
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <!-- Back Button & Banner Content -->
        <div class="max-w-3xl mx-auto flex items-center gap-4 relative z-10">
            <a href="{{ route('profile.edit') }}"
               class="w-10 h-10 rounded-full bg-white/10 border border-white/25 hover:bg-white/20 flex items-center justify-center text-white transition-colors shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-none uppercase tracking-wide">Edit Profil</h3>
                <p class="text-xs md:text-sm text-white/80 font-medium mt-1">Perbarui informasi dasar akun Anda</p>
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

<!-- Form Container (Centered and padded for mobile bottom bar) -->
<div class="max-w-xl mx-auto px-4 md:px-0 mt-8 pb-24 md:pb-6 relative z-20">

    <!-- Status Alerts -->
    @if (session('status') === 'profile-updated')
        <x-alert type="success" message="Perubahan Profil Berhasil Disimpan" class="mb-6" />
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Nama Lengkap Card -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <label for="name" class="font-extrabold text-xs text-slate-500 uppercase tracking-widest">Nama Lengkap</label>
            </div>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full text-center font-extrabold text-slate-800 bg-white border-2 border-slate-200 focus:border-primary focus:ring-primary rounded-2xl py-3 px-4 shadow-sm text-base transition-colors duration-150">
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- Email Card -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <label for="email" class="font-extrabold text-xs text-slate-500 uppercase tracking-widest">Alamat Email</label>
            </div>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full text-center font-extrabold text-slate-800 bg-white border-2 border-slate-200 focus:border-primary focus:ring-primary rounded-2xl py-3 px-4 shadow-sm text-base transition-colors duration-150">
            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <!-- Verified Status Badge / Email Verification Warning -->
        @if (!$user->hasVerifiedEmail())
            <div class="bg-red-50 border-2 border-red-200 text-red-800 rounded-3xl p-5 shadow-sm text-center space-y-3 mt-4">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation text-xl animate-pulse"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm uppercase tracking-wide text-red-950">Email Belum Terverifikasi</h4>
                    <p class="text-xs text-red-700/95 leading-relaxed mt-1">
                        Alamat email Anda belum terverifikasi di sistem kami. Harap verifikasi email Anda agar dapat mengelola data e-Rapor dengan aman dan mengakses seluruh fitur.
                    </p>
                </div>

                <div x-data="{ loading: false }">
                    <button type="button" :disabled="loading" @click="loading = true; document.getElementById('send-verification-details').submit();"
                            class="w-full px-6 py-3 bg-[#FFD028] hover:bg-[#FFD028]/90 text-slate-950 font-extrabold text-xs uppercase tracking-widest rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                        <template x-if="loading">
                            <i class="fa-solid fa-spinner animate-spin"></i>
                        </template>
                        <template x-if="!loading">
                            <i class="fa-solid fa-paper-plane"></i>
                        </template>
                        <span x-text="loading ? 'Mengirim Verifikasi...' : 'Verifikasi Email Sekarang'"></span>
                    </button>
                </div>
            </div>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success" message="Tautan verifikasi baru telah dikirim ke alamat email Anda." class="mt-4" />
            @endif
        @else
            <x-alert type="success" message="Email Telah Terverifikasi" />
        @endif

        <!-- Floating Bottom Save Button Bar on Mobile, Normal Inline Button on Desktop -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-100 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] md:shadow-none rounded-t-3xl md:rounded-none z-40 md:static md:bg-transparent md:border-0 md:p-0 md:mt-8 flex justify-center md:justify-start">
            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs uppercase tracking-widest rounded-2xl md:rounded-xl shadow-md transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <form id="send-verification-details" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>
</div>
@endsection
