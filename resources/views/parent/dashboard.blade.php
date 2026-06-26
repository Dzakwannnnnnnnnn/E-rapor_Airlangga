@extends('layouts.dashboard')

@section('title', 'Dashboard - e-Rapor')

@section('content')

@php
    $parentName = Auth::user()->name;
    // Tentukan waktu sapa
    $hour = (int) now()->format('H');
    if ($hour >= 5 && $hour < 12)       $greet = 'Selamat Pagi';
    elseif ($hour >= 12 && $hour < 15)  $greet = 'Selamat Siang';
    elseif ($hour >= 15 && $hour < 18)  $greet = 'Selamat Sore';
    else                                $greet = 'Selamat Malam';

    // Label performa nilai
    $gradeLabel = $avgScore !== null
        ? ($avgScore >= 88 ? 'Sangat Baik' : ($avgScore >= 75 ? 'Baik' : 'Perlu Perhatian'))
        : '—';
    $gradeLabelColor = $avgScore !== null
        ? ($avgScore >= 88 ? 'text-emerald-600' : ($avgScore >= 75 ? 'text-blue-600' : 'text-amber-600'))
        : 'text-slate-400';

    // Label kehadiran
    $attendLabel = $attendancePct !== null
        ? ($attendancePct >= 95 ? 'Sangat Baik' : ($attendancePct >= 80 ? 'Baik' : 'Kurang'))
        : '—';
    $attendLabelColor = $attendancePct !== null
        ? ($attendancePct >= 95 ? 'text-emerald-600' : ($attendancePct >= 80 ? 'text-blue-600' : 'text-amber-600'))
        : 'text-slate-400';
@endphp

{{-- ────────────────── HERO GREETING ────────────────── --}}
<div class="mb-6">
    <p class="text-xs font-bold text-muted uppercase tracking-widest mb-1">{{ $greet }},</p>
    <h1 class="text-2xl md:text-3xl font-black text-text tracking-tight leading-tight">
        Bapak/Ibu <span class="text-primary">{{ $parentName }}</span> 👋
    </h1>
    <p class="text-sm text-muted mt-1.5">Berikut perkembangan belajar anak Anda hari ini.</p>
</div>

@if(!$student)
    {{-- State: Siswa tidak ditemukan --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
        <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-100">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-2xl"></i>
        </div>
        <h3 class="text-base font-black text-slate-800 mb-1">Siswa Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">Akun Anda belum dihubungkan ke profil siswa manapun. Hubungi administrator sekolah.</p>
    </div>
@else

    {{-- ────────── STUDENT IDENTITY CARD ────────── --}}
    <div class="relative bg-primary rounded-2xl p-6 mb-6 overflow-hidden shadow-lg">
        {{-- Decorative circles --}}
        <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-secondary/10 rounded-full pointer-events-none"></div>
        <div class="absolute top-4 right-20 w-16 h-16 bg-white/5 rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-4">
            {{-- Avatar inisial --}}
            @php
                $sName = $student->name;
                $parts = explode(' ', $sName);
                $init  = strtoupper(substr($parts[0], 0, 1));
                if (count($parts) > 1) $init .= strtoupper(substr($parts[1], 0, 1));
            @endphp
            <div class="w-14 h-14 rounded-2xl bg-secondary text-primary font-black text-xl flex items-center justify-center shrink-0 shadow-md">
                {{ $init }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-white font-black text-lg leading-tight truncate">{{ $student->name }}</h2>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="inline-flex items-center gap-1 bg-white/15 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                        <i class="fa-solid fa-school text-secondary text-[9px]"></i>
                        {{ $student->classroom->name ?? 'N/A' }}
                    </span>
                    @if($student->nisn)
                    <span class="inline-flex items-center gap-1 bg-white/10 text-white/70 text-[10px] font-bold px-2.5 py-1 rounded-full">
                        NISN: {{ $student->nisn }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="shrink-0 text-right hidden sm:block">
                <span class="text-[9px] text-white/50 font-bold uppercase tracking-widest block">Jurusan</span>
                <span class="text-white font-black text-sm">{{ $student->classroom->major ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- ────────── RINGKASAN 4 KARTU ────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- 1. Rata-rata Nilai --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-chart-line text-blue-600 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Rata-rata</span>
            </div>
            <p class="text-3xl font-black text-primary tracking-tight leading-none">
                {{ $avgScore !== null ? number_format($avgScore, 1) : '—' }}
            </p>
            <div class="mt-2.5 flex items-center gap-1.5">
                @if($avgScore !== null)
                    <i class="fa-solid fa-arrow-trend-up text-[10px] {{ $gradeLabelColor }}"></i>
                @endif
                <span class="text-[10px] font-bold {{ $gradeLabelColor }} uppercase tracking-wide">{{ $gradeLabel }}</span>
            </div>
        </div>

        {{-- 2. Kehadiran --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                    <i class="fa-solid fa-calendar-check text-emerald-600 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kehadiran</span>
            </div>
            <p class="text-3xl font-black text-emerald-600 tracking-tight leading-none">
                {{ $attendancePct !== null ? $attendancePct . '%' : '—' }}
            </p>
            <div class="mt-2.5 flex items-center gap-1.5">
                <span class="text-[10px] font-bold {{ $attendLabelColor }} uppercase tracking-wide">{{ $attendLabel }}</span>
            </div>
        </div>

        {{-- 3. Tugas Selesai --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center group-hover:bg-violet-600 transition-colors">
                    <i class="fa-solid fa-clipboard-check text-violet-600 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Tugas</span>
            </div>
            <p class="text-3xl font-black text-violet-600 tracking-tight leading-none">
                @if($totalAssignments > 0)
                    {{ $doneAssignments }}<span class="text-lg text-slate-300 font-bold">/{{ $totalAssignments }}</span>
                @else
                    <span class="text-slate-300">—</span>
                @endif
            </p>
            <div class="mt-2.5">
                <span class="text-[10px] font-bold {{ $taskPct !== null && $taskPct >= 90 ? 'text-emerald-600' : ($taskPct !== null && $taskPct >= 70 ? 'text-amber-600' : 'text-red-500') }} uppercase tracking-wide">
                    {{ $taskPct !== null ? $taskPct . '% selesai' : 'Belum ada' }}
                </span>
            </div>
        </div>

        {{-- 4. Peringkat Kelas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                    <i class="fa-solid fa-trophy text-amber-500 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Peringkat</span>
            </div>
            <p class="text-3xl font-black text-amber-500 tracking-tight leading-none">
                @if($reportCard && $reportCard->rank)
                    #{{ $reportCard->rank }}<span class="text-lg text-slate-300 font-bold">/{{ $totalStudents }}</span>
                @else
                    <span class="text-slate-300">—</span>
                @endif
            </p>
            <div class="mt-2.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                    {{ $reportCard && $reportCard->rank ? 'Dari ' . $totalStudents . ' siswa' : 'Belum dihitung' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ────────── CATATAN WALI KELAS + QUICK LINKS ────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Catatan Wali Kelas --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-slate-50">
                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-comment-dots text-emerald-600 text-xs"></i>
                </div>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Catatan Wali Kelas</h3>
            </div>
            @if($reportCard && $reportCard->description)
                <div class="relative bg-gradient-to-br from-primary/5 to-blue-50/50 border-l-4 border-primary p-4 rounded-r-xl">
                    <i class="fa-solid fa-quote-left text-primary/20 text-3xl absolute -top-1 -left-2 leading-none"></i>
                    <p class="text-sm text-slate-700 italic leading-relaxed pl-4">
                        "{{ $reportCard->description }}"
                    </p>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                        <i class="fa-solid fa-comment-slash text-slate-300 text-lg"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-400">Belum ada catatan dari wali kelas.</p>
                </div>
            @endif
        </div>

        {{-- Quick Links --}}
        <div class="flex flex-col gap-3">
            <a href="{{ route('parent.academic') }}"
               class="group flex items-center gap-3.5 bg-white hover:bg-primary rounded-2xl border border-slate-100 shadow-sm p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                <div class="w-10 h-10 bg-blue-50 group-hover:bg-white/20 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                    <i class="fa-solid fa-book-open text-blue-600 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-slate-800 group-hover:text-white transition-colors uppercase tracking-tight leading-none">Lihat Detail Nilai</p>
                    <p class="text-[9px] text-slate-400 group-hover:text-white/70 mt-0.5 transition-colors">Semua mata pelajaran</p>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-white/70 text-xs shrink-0 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <a href="{{ route('parent.attendance') }}"
               class="group flex items-center gap-3.5 bg-white hover:bg-emerald-600 rounded-2xl border border-slate-100 shadow-sm p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                <div class="w-10 h-10 bg-emerald-50 group-hover:bg-white/20 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                    <i class="fa-solid fa-calendar-days text-emerald-600 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-slate-800 group-hover:text-white transition-colors uppercase tracking-tight leading-none">Detail Kehadiran</p>
                    <p class="text-[9px] text-slate-400 group-hover:text-white/70 mt-0.5 transition-colors">Hadir, Sakit, Izin, Alpha</p>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-white/70 text-xs shrink-0 group-hover:translate-x-0.5 transition-all"></i>
            </a>

            <a href="{{ route('parent.report') }}"
               class="group flex items-center gap-3.5 bg-white hover:bg-amber-500 rounded-2xl border border-slate-100 shadow-sm p-4 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5">
                <div class="w-10 h-10 bg-amber-50 group-hover:bg-white/20 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                    <i class="fa-solid fa-file-circle-check text-amber-500 group-hover:text-white text-sm transition-colors"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-slate-800 group-hover:text-white transition-colors uppercase tracking-tight leading-none">Cetak Rapor</p>
                    <p class="text-[9px] text-slate-400 group-hover:text-white/70 mt-0.5 transition-colors">Unduh PDF resmi</p>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-white/70 text-xs shrink-0 group-hover:translate-x-0.5 transition-all"></i>
            </a>
        </div>
    </div>

@endif
@endsection
