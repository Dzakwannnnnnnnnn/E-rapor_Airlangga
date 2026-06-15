@extends('layouts.app')

@section('title', 'Verifikasi Email - e-Rapor')

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

        <button type="submit" form="logoutForm" class="absolute top-4 left-6 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-red-600/20 border border-white/20 rounded-full text-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-20 transition-all backdrop-blur-md cursor-pointer" title="Keluar / Log Out">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </button>

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
                Verifikasi <br>
                <span class="text-[#FFB800]">Email Anda</span>
            </h2>
            <p class="text-[11px] text-white/70 max-w-[320px] font-normal leading-relaxed">
                Terima kasih telah mendaftar! Sebelum melanjutkan, silakan klik tautan verifikasi yang baru saja kami kirimkan ke email Anda.
            </p>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 bg-[#FFB800] z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-[#F4F6F9] z-10" style="clip-path: polygon(0 100%, 100% 0, 100% 100%, 0 100%);"></div>
    </div>

    <div class="flex-1 px-6 pt-8 w-full max-w-md mx-auto relative z-10 pb-28 lg:pb-12">

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl text-xs font-bold text-emerald-800 leading-relaxed shadow-sm flex items-start gap-2.5 animate-fadeIn">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                <span>Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda tentukan saat registrasi.</span>
            </div>
        @endif

        <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-xl text-xs font-bold text-slate-600 leading-relaxed shadow-sm flex items-start gap-2.5">
            <i class="fa-solid fa-circle-info text-[#003399] text-sm mt-0.5"></i>
            <span>Belum menerima email dari kami? Periksa folder Spam atau klik tombol di bawah untuk mengirim ulang tautan verifikasi.</span>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" id="resendVerificationForm" class="flex flex-col">
            @csrf

            <button type="submit" class="hidden lg:flex w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 items-center justify-center gap-2 border-b-2 border-slate-900/20">
                <i class="fa-solid fa-paper-plane text-[#FFB800]"></i>
                Kirim Ulang Tautan Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" id="logoutForm" class="mt-6 flex justify-center">
            @csrf
            <button type="submit" class="text-[10px] font-black text-slate-400 hover:text-red-600 uppercase tracking-widest transition-colors flex items-center gap-1.5 bg-transparent border-none outline-none cursor-pointer">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar dari Aplikasi
            </button>
        </form>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 pb-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-md mx-auto">
            <button type="submit" form="resendVerificationForm" class="w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 flex items-center justify-center gap-2 border-b-2 border-slate-900/20">
                <i class="fa-solid fa-paper-plane text-[#FFB800]"></i>
                Kirim Ulang Tautan Verifikasi
            </button>
        </div>
    </div>
</div>
@endsection
