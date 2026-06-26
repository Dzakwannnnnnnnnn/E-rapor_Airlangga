@extends('layouts.dashboard')

@section('title', 'Rapor - e-Rapor')
@section('back_url', route('parent.dashboard'))

@section('content')

@php
    $studentName = $student ? $student->name : '';
@endphp

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-xl font-black text-text uppercase tracking-tight leading-none">Rapor Resmi</h1>
    <p class="text-[11px] text-muted font-bold uppercase tracking-wider mt-1">
        Siswa: <span class="text-primary">{{ $studentName }}</span>
        @if($student && $student->classroom)
            &mdash; {{ $student->classroom->name }}
        @endif
    </p>
</div>

@if(!$student)
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-3xl block mb-3"></i>
        <p class="text-sm font-black text-slate-700">Siswa tidak ditemukan.</p>
    </div>

@elseif($reportCard && $reportCard->is_validated)
    {{-- ──── RAPOR SUDAH DISAHKAN ──── --}}

    {{-- Status badge --}}
    <div class="flex items-center gap-2 mb-5 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3">
        <div class="w-7 h-7 bg-emerald-600 rounded-lg flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-check text-white text-xs"></i>
        </div>
        <div>
            <p class="text-xs font-black text-emerald-800 uppercase tracking-wide leading-none">Rapor Resmi Telah Disahkan</p>
            <p class="text-[10px] text-emerald-600 mt-0.5">
                Tahun Ajaran: {{ $reportCard->academicYear->name ?? '—' }}
            </p>
        </div>
    </div>

    {{-- Main report card display --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">
        {{-- Header rapor --}}
        <div class="bg-primary p-6 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
            <div class="absolute -left-4 -bottom-6 w-24 h-24 bg-secondary/10 rounded-full pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-secondary rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-primary text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-black text-sm uppercase tracking-wider leading-none">e-Rapor Resmi</p>
                        <p class="text-secondary text-[10px] font-bold uppercase tracking-widest">SMKTI Airlangga Samarinda</p>
                    </div>
                </div>
                <h2 class="text-white font-black text-xl leading-tight">{{ $student->name }}</h2>
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider">
                    {{ $student->classroom->name ?? '' }} &middot; {{ $student->nisn ?? '' }}
                </p>
            </div>
        </div>

        {{-- Metrics --}}
        <div class="grid grid-cols-3 divide-x divide-slate-50 border-b border-slate-50">
            <div class="p-5 text-center">
                <p class="text-[9px] font-bold text-muted uppercase tracking-widest mb-1">Nilai Rata-rata</p>
                <p class="text-3xl font-black text-primary">{{ number_format($reportCard->final_score, 2) }}</p>
                <p class="text-[10px] font-bold {{ $reportCard->final_score >= 88 ? 'text-emerald-600' : ($reportCard->final_score >= 75 ? 'text-blue-600' : 'text-amber-600') }} mt-0.5">
                    {{ $reportCard->final_score >= 88 ? 'Sangat Baik' : ($reportCard->final_score >= 75 ? 'Baik' : 'Cukup') }}
                </p>
            </div>
            <div class="p-5 text-center">
                <p class="text-[9px] font-bold text-muted uppercase tracking-widest mb-1">Peringkat</p>
                <p class="text-3xl font-black text-secondary">#{{ $reportCard->rank ?? '—' }}</p>
                <p class="text-[10px] font-bold text-muted mt-0.5">dari {{ $totalStudents }} siswa</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-[9px] font-bold text-muted uppercase tracking-widest mb-1">Status</p>
                <div class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 rounded-full px-2.5 py-1 mt-1">
                    <i class="fa-solid fa-check text-[9px]"></i>
                    <span class="text-[10px] font-black uppercase">Lulus</span>
                </div>
            </div>
        </div>

        {{-- Catatan wali kelas --}}
        @if($reportCard->description)
        <div class="p-5">
            <p class="text-[10px] font-bold text-muted uppercase tracking-widest mb-2">Catatan Wali Kelas</p>
            <div class="bg-primary/5 border-l-4 border-primary p-4 rounded-r-xl">
                <p class="text-sm text-slate-700 italic leading-relaxed">"{{ $reportCard->description }}"</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Download PDF --}}
    <div class="bg-primary rounded-2xl p-6 relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 text-white/5 text-[10rem] pointer-events-none leading-none">
            <i class="fa-solid fa-file-pdf"></i>
        </div>
        <div class="relative z-10 flex items-center justify-between gap-4">
            <div>
                <h3 class="text-white font-black text-base uppercase tracking-tight leading-none mb-1">Unduh e-Rapor PDF</h3>
                <p class="text-secondary text-[10px] font-bold uppercase tracking-widest">Format PDF resmi siap cetak</p>
            </div>
            <button type="button"
                    onclick="alert('Fitur unduh PDF akan segera tersedia.')"
                    class="shrink-0 bg-secondary hover:bg-yellow-400 text-primary font-black text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition-all shadow-lg hover:shadow-xl hover:scale-105 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-download"></i>
                Unduh PDF
            </button>
        </div>
    </div>

@else
    {{-- ──── RAPOR BELUM DISAHKAN ──── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 md:p-12 text-center max-w-2xl mx-auto relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-secondary/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-primary/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col items-center">
            <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-6 shadow-sm border border-amber-100">
                <i class="fa-solid fa-hourglass-half text-3xl animate-pulse"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight leading-none mb-3">Rapor Belum Disahkan</h2>
            <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                Hasil laporan akademik untuk <span class="text-primary font-bold">{{ $studentName }}</span> saat ini sedang dalam proses penyusunan oleh Wali Kelas dan belum disahkan oleh Kepala Sekolah / Admin.
            </p>

            @if($reportCard && $reportCard->is_submitted)
                <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl px-5 py-3.5 inline-flex items-center gap-2.5">
                    <i class="fa-solid fa-clock text-blue-500 text-sm"></i>
                    <span class="text-xs text-blue-700 font-bold">Rapor sudah diajukan, menunggu pengesahan admin.</span>
                </div>
            @endif

            <div class="mt-6 px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl inline-flex items-center gap-2.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                <i class="fa-solid fa-circle-info text-amber-500 text-xs"></i>
                Silakan periksa kembali secara berkala.
            </div>
        </div>
    </div>
@endif

@endsection
