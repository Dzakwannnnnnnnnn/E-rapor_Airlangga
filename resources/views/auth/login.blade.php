@extends('layouts.app')

@section('title', 'Login - e-Rapor')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] flex flex-col relative font-sans selection:bg-[#003399]/25 selection:text-[#003399]">

    <div class="relative bg-[#003399] pt-10 pb-20 overflow-hidden z-0">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-10"></div>
        <div class="absolute bottom-12 right-6 opacity-20 grid grid-cols-4 gap-1.5 pointer-events-none z-10">
            @for ($i = 0; $i < 12; $i++)
                <div class="w-1 h-1 bg-[#FFB800] rounded-full"></div>
            @endfor
        </div>
        <div class="absolute top-10 right-[10%] w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none z-0"></div>

        <a href="/" class="absolute top-4 left-6 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-20 transition-all backdrop-blur-md">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>

        <div class="px-6 relative z-20 mt-8 max-w-md mx-auto">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-white p-2 rounded-xl shadow-md border-b-2 border-[#FFB800]">
                    <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-lg font-black text-white tracking-tight uppercase leading-none">e-Rapor</h1>
                    <p class="text-[9px] text-[#FFB800] font-bold uppercase tracking-widest mt-0.5">SMK TI Airlangga</p>
                </div>
            </div>

            <h2 class="text-3xl font-black text-white tracking-tight leading-none mb-2 uppercase">
                Sistem <br>
                <span class="text-[#FFB800]">Login Akun</span>
            </h2>
            <p class="text-[11px] text-white/70 max-w-[250px] font-normal leading-relaxed">
                Silakan masuk menggunakan kredensial Anda untuk mengakses dasbor akademik siswa.
            </p>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 bg-[#FFB800] z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-[#F4F6F9] z-10" style="clip-path: polygon(0 100%, 100% 0, 100% 100%, 0 100%);"></div>
    </div>

    <div class="flex-1 px-6 pt-8 w-full max-w-md mx-auto relative z-10 pb-28 lg:pb-12">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" id="loginForm" class="flex flex-col">
            @csrf
            <div class="mb-5 relative group">
                <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute bottom-4 left-4 text-slate-400 group-focus-within:text-[#003399] transition-colors z-10">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda"
                        class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:border-[#003399] focus:ring-2 focus:ring-[#003399]/20 transition-all outline-none shadow-sm">
                </div>
            </div>

            <div class="mb-6 relative group">
                <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute bottom-4 left-4 text-slate-400 group-focus-within:text-[#003399] transition-colors z-10">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" maxlength="16"
                        class="w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:border-[#003399] focus:ring-2 focus:ring-[#003399]/20 transition-all outline-none shadow-sm">
                    <button type="button" onclick="togglePw('password', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                        <i class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer ml-1">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-[#003399] w-3.5 h-3.5">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ingat sesi saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[10px] font-black text-[#003399] hover:text-[#FFB800] uppercase tracking-wider transition-colors mr-1">Lupa sandi?</a>
                @endif
            </div>

            <button type="submit" class="hidden lg:flex w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 items-center justify-center gap-2 border-b-2 border-slate-900/20">
                <i class="fa-solid fa-right-to-bracket text-[#FFB800]"></i>
                Masuk
            </button>
        </form>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 pb-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-md mx-auto">
            <button type="submit" form="loginForm" class="w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 flex items-center justify-center gap-2 border-b-2 border-slate-900/20">
                <i class="fa-solid fa-right-to-bracket text-[#FFB800]"></i>
                Masuk
            </button>
        </div>
    </div>
</div>

<script>
    function togglePw(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection
