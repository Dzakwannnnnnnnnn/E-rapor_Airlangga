@extends('layouts.dashboard')

@section('title', 'Kehadiran - e-Rapor')
@section('back_url', route('parent.dashboard'))

@section('content')

@php
    $studentName = $student ? $student->name : '';

    $hadir = $attendance->hadir ?? 0;
    $sakit = $attendance->sakit ?? 0;
    $izin  = $attendance->izin ?? 0;
    $alpha = $attendance->alpha ?? 0;
    $total = $hadir + $sakit + $izin + $alpha;

    $hadirPct = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
    $sakitPct = $total > 0 ? round(($sakit / $total) * 100, 1) : 0;
    $izinPct  = $total > 0 ? round(($izin  / $total) * 100, 1) : 0;
    $alphaPct = $total > 0 ? round(($alpha / $total) * 100, 1) : 0;

    $attendLabel = $hadirPct >= 95 ? 'Sangat Baik' : ($hadirPct >= 80 ? 'Baik' : 'Perlu Perhatian');
    $attendColor = $hadirPct >= 95 ? '#22C55E' : ($hadirPct >= 80 ? '#1D4ED8' : '#FACC15');
@endphp

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-xl font-black text-text uppercase tracking-tight leading-none">Kehadiran Semester</h1>
    <p class="text-[11px] text-muted font-bold uppercase tracking-wider mt-1">
        Siswa: <span class="text-primary">{{ $studentName }}</span>
        @if($student && $student->classroom)
            &mdash; {{ $student->classroom->name }}
        @endif
    </p>
</div>

@if(!$student || !$attendance)
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
        <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-calendar-xmark text-slate-300 text-2xl"></i>
        </div>
        <p class="text-sm font-black text-slate-700 mb-1">Data Kehadiran Tidak Tersedia</p>
        <p class="text-xs text-slate-400">Data kehadiran belum diinput untuk semester ini.</p>
    </div>
@else

    {{-- ── Donut summary card ── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-5">
        <div class="flex flex-col md:flex-row items-center gap-6">

            {{-- Donut Chart (SVG) --}}
            <div class="shrink-0 relative w-36 h-36">
                @php
                    $r = 52;
                    $cx = 72; $cy = 72;
                    $circumference = 2 * M_PI * $r;
                    $hadirDash  = ($circumference * $hadirPct / 100);
                    $sakitDash  = ($circumference * $sakitPct / 100);
                    $izinDash   = ($circumference * $izinPct  / 100);
                    $alphaDash  = ($circumference * $alphaPct / 100);
                    // offsets
                    $hadirOffset = 0;
                    $sakitOffset = -$hadirDash;
                    $izinOffset  = -($hadirDash + $sakitDash);
                    $alphaOffset = -($hadirDash + $sakitDash + $izinDash);
                @endphp
                <svg viewBox="0 0 144 144" class="w-36 h-36 -rotate-90">
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none" stroke="#F1F5F9" stroke-width="18"/>
                    {{-- Hadir --}}
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                        stroke="#22C55E" stroke-width="18"
                        stroke-dasharray="{{ $hadirDash }} {{ $circumference - $hadirDash }}"
                        stroke-dashoffset="{{ $hadirOffset }}"
                        stroke-linecap="butt"/>
                    {{-- Sakit --}}
                    @if($sakitPct > 0)
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                        stroke="#3B82F6" stroke-width="18"
                        stroke-dasharray="{{ $sakitDash }} {{ $circumference - $sakitDash }}"
                        stroke-dashoffset="{{ $sakitOffset }}"
                        stroke-linecap="butt"/>
                    @endif
                    {{-- Izin --}}
                    @if($izinPct > 0)
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                        stroke="#F59E0B" stroke-width="18"
                        stroke-dasharray="{{ $izinDash }} {{ $circumference - $izinDash }}"
                        stroke-dashoffset="{{ $izinOffset }}"
                        stroke-linecap="butt"/>
                    @endif
                    {{-- Alpha --}}
                    @if($alphaPct > 0)
                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                        stroke="#EF4444" stroke-width="18"
                        stroke-dasharray="{{ $alphaDash }} {{ $circumference - $alphaDash }}"
                        stroke-dashoffset="{{ $alphaOffset }}"
                        stroke-linecap="butt"/>
                    @endif
                </svg>
                {{-- Center label --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black" style="color: {{ $attendColor }}">{{ $hadirPct }}%</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest -mt-0.5">Hadir</span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex-1 w-full">
                <div class="flex items-baseline gap-2 mb-1">
                    <p class="text-sm font-black text-slate-800">Ringkasan Kehadiran</p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                        {{ $hadirPct >= 95 ? 'bg-emerald-50 text-emerald-600' : ($hadirPct >= 80 ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600') }}">
                        {{ $attendLabel }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mb-5">Total {{ $total }} hari tercatat semester ini.</p>

                <div class="grid grid-cols-2 gap-3">
                    {{-- Hadir --}}
                    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-check text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-emerald-700 leading-none">{{ $hadir }}</p>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Hadir</p>
                        </div>
                        <span class="ml-auto text-[10px] font-bold text-emerald-400">{{ $hadirPct }}%</span>
                    </div>

                    {{-- Sakit --}}
                    <div class="rounded-xl bg-blue-50 border border-blue-100 p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-heart-pulse text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-blue-700 leading-none">{{ $sakit }}</p>
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Sakit</p>
                        </div>
                        <span class="ml-auto text-[10px] font-bold text-blue-400">{{ $sakitPct }}%</span>
                    </div>

                    {{-- Izin --}}
                    <div class="rounded-xl bg-amber-50 border border-amber-100 p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 bg-amber-500 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file-lines text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-amber-700 leading-none">{{ $izin }}</p>
                            <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Izin</p>
                        </div>
                        <span class="ml-auto text-[10px] font-bold text-amber-400">{{ $izinPct }}%</span>
                    </div>

                    {{-- Alpha --}}
                    <div class="rounded-xl bg-red-50 border border-red-100 p-3.5 flex items-center gap-3">
                        <div class="w-9 h-9 bg-red-500 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-xmark text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xl font-black text-red-700 leading-none">{{ $alpha }}</p>
                            <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider">Alpha</p>
                        </div>
                        <span class="ml-auto text-[10px] font-bold text-red-400">{{ $alphaPct }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Keterangan info --}}
    @if($alpha > 2)
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 shrink-0"></i>
            <p class="text-xs text-red-700 leading-relaxed">
                <strong>Perhatian:</strong> Anak Anda tercatat <strong>{{ $alpha }} kali Alpha</strong> tanpa keterangan. Segera hubungi wali kelas untuk klarifikasi.
            </p>
        </div>
    @elseif($hadirPct >= 95)
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-star text-emerald-500 mt-0.5 shrink-0"></i>
            <p class="text-xs text-emerald-700 leading-relaxed">
                <strong>Luar Biasa!</strong> Kehadiran anak Anda sangat baik ({{ $hadirPct }}%). Pertahankan disiplin ini!
            </p>
        </div>
    @endif

@endif

@endsection
