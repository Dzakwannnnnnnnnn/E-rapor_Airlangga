@extends('layouts.dashboard')

@section('title', 'Wali Kelas - e-Rapor')

@section('content')
<div class="max-w-md mx-auto my-12 text-center bg-white p-8 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center">
    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4 border border-slate-100">
        <i class="fa-solid fa-chalkboard-user text-2xl"></i>
    </div>
    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Bukan Wali Kelas</h3>
    <p class="text-sm text-slate-500 mt-2 font-medium">
        Anda tidak terdaftar sebagai Wali Kelas pada Rombongan Belajar manapun untuk Tahun Ajaranaktif.
    </p>
    <p class="text-xs text-slate-400 mt-1 font-semibold uppercase">
        Hubungi administrator jika Anda seharusnya menjadi Wali Kelas.
    </p>
    <a href="{{ route('teacher.dashboard') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-blue-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm">
        <i class="fa-solid fa-house"></i> Kembali ke Dashboard
    </a>
</div>
@endsection
