@extends('layouts.app')

@section('title', 'Tautan Kedaluwarsa - e-Rapor')

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

        <a href="{{ route('login') }}" class="absolute top-4 left-6 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-20 transition-all backdrop-blur-md">
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
                Tautan <br>
                <span class="text-[#FFB800]">Kedaluwarsa</span>
            </h2>
            <p class="text-[11px] text-white/70 max-w-[280px] font-normal leading-relaxed">
                Batas waktu verifikasi keamanan token Anda telah berakhir demi menjaga integritas data akses pengguna.
            </p>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 bg-[#FFB800] z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-[#F4F6F9] z-10" style="clip-path: polygon(0 100%, 100% 0, 100% 100%, 0 100%);"></div>
    </div>

    <div class="flex-1 px-6 pt-6 w-full max-w-md mx-auto relative z-10 pb-28 lg:pb-12">

        <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-6 text-center shadow-sm select-none">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center mx-auto mb-4 shadow-sm animate-pulse">
                <i class="fa-solid fa-clock text-2xl"></i>
            </div>

            <div class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full mb-3">
                <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                Masa Berlaku Habis
            </div>

            <h3 class="text-slate-900 text-xs font-black uppercase tracking-wider mb-2">Batas Waktu Aktivasi Terlampaui</h3>
            <p class="text-slate-600 text-xs font-medium leading-relaxed">
                Tautan aktivasi akun Anda telah <strong class="text-amber-600 font-bold">melewati batas masa aktif 24 jam</strong> sejak pertama kali dikirimkan oleh sistem komputerisasi sekolah.
            </p>

            <div class="mt-5 bg-slate-50 border border-slate-200 rounded-xl p-3.5 flex items-start gap-3 text-left">
                <div class="w-7 h-7 rounded-lg bg-[#003399]/10 text-[#003399] flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-info text-xs"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-[10px] font-black text-slate-700 uppercase tracking-wide mb-0.5">Langkah Sinkronisasi</h4>
                    <p class="text-slate-500 text-[11px] font-semibold leading-normal">
                        Hubungi Unit Administrator e-Rapor SMK TI Airlangga dengan menyertakan alamat email resmi Anda agar sistem dapat memvalidasi kredensial dan menerbitkan ulang tautan baru.
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('login') }}" class="hidden lg:flex w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 items-center justify-center gap-2 border-b-2 border-slate-900/20 cursor-pointer">
            <i class="fa-solid fa-right-to-bracket text-[#FFB800]"></i>
            Kembali ke Halaman Login
        </a>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 pb-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-md mx-auto">
            <a href="{{ route('login') }}" class="w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 flex items-center justify-center gap-2 border-b-2 border-slate-900/20 cursor-pointer">
                <i class="fa-solid fa-right-to-bracket text-[#FFB800]"></i>
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>
@endsection
