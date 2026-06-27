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

    // Perhitungan persentase presisi untuk bar
    $hadirPct = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
    $sakitPct = $total > 0 ? round(($sakit / $total) * 100, 1) : 0;
    $izinPct  = $total > 0 ? round(($izin  / $total) * 100, 1) : 0;
    $alphaPct = $total > 0 ? round(($alpha / $total) * 100, 1) : 0;

    // Angka bulat di dalam Donut Chart
    $donutPct = $total > 0 ? round(($hadir / $total) * 100, 0) : 0;

    // Logika Predikat Label
    $attendLabel = $hadirPct >= 95 ? 'Sangat Baik' : ($hadirPct >= 80 ? 'Baik' : 'Perlu Perhatian');
    $attendBadgeColor = $hadirPct >= 95 ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : ($hadirPct >= 80 ? 'text-blue-600 bg-blue-50 border-blue-200' : 'text-amber-600 bg-amber-50 border-amber-200');
@endphp

{{-- Hero Header Section --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Kehadiran Siswa</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-user text-sm text-secondary"></i>
                            <span>Siswa: <strong class="text-white">{{ $studentName }}</strong></span>
                            @if($student && $student->classroom)
                                <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] font-bold">{{ $student->classroom->name }}</span>
                            @endif
                        </div>
                        @if(isset($selectedYear) && $selectedYear)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar-alt text-sm text-secondary"></i>
                                <span>
                                    {{ ucfirst($selectedYear->semester) }} {{ $selectedYear->year }}
                                    @if($selectedYear->is_active)
                                        <span class="ml-1 bg-secondary/80 text-slate-900 px-1.5 py-0.5 rounded text-[10px] font-extrabold">Aktif</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Filter Dropdown Semester (Dinamis disamakan dengan Akademik) --}}
            @if(isset($academicYears))
            <div class="relative shrink-0 self-center md:self-start">
                <form method="GET" action="{{ url()->current() }}" id="semester-filter-form">
                    <div class="relative">
                        <select
                            name="academic_year_id"
                            id="semester-select"
                            onchange="document.getElementById('semester-filter-form').submit()"
                            class="appearance-none bg-white/10 hover:bg-white/20 border border-white/20 shadow-sm rounded-xl pl-4 pr-10 py-2.5 text-xs font-bold text-white focus:outline-none cursor-pointer backdrop-blur-sm transition-all min-w-[180px]">
                            @foreach($academicYears as $ay)
                                <option
                                    value="{{ $ay->id }}"
                                    class="text-slate-800 bg-white"
                                    {{ (isset($selectedYear) && $selectedYear && $selectedYear->id == $ay->id) ? 'selected' : '' }}>
                                    {{ ucfirst($ay->semester) }} {{ $ay->year }}
                                    {{ $ay->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

{{-- Content Area --}}
<div class="max-w-6xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16 relative z-20">

    @if(!$student || !$attendance)
        <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center shadow-sm -mt-16">
            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <i class="fa-solid fa-calendar-xmark text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-700 mb-1">Data Tidak Tersedia</p>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">Data rekapitulasi kehadiran belum diinput atau tidak ditemukan untuk periode ini.</p>
        </div>
    @else

        {{-- Overlapping Card: Ringkasan & Donut Chart --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 flex flex-col md:flex-row items-center justify-between gap-8 -mt-16">

            {{-- Bagian Kiri: Donut Chart Persentase --}}
            <div class="flex flex-col sm:flex-row items-center gap-6 shrink-0 w-full md:w-auto border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-8 justify-center md:justify-start">
                <div class="relative w-32 h-32 flex items-center justify-center bg-slate-50 rounded-full p-1.5 shadow-inner shrink-0">
                    @php
                        $r = 54;
                        $cx = 72; $cy = 72;
                        $circumference = 2 * M_PI * $r;
                        $hadirDash = ($circumference * $donutPct / 100);
                    @endphp
                    <svg viewBox="0 0 144 144" class="w-full h-full -rotate-90">
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none" stroke="#F1F5F9" stroke-width="12"/>
                        <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                            stroke="#22C55E" stroke-width="12"
                            stroke-dasharray="{{ $hadirDash }} {{ $circumference - $hadirDash }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-3 bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                        <span class="text-2xl font-black text-slate-800 tracking-tight leading-none">{{ $donutPct }}%</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Hadir</span>
                    </div>
                </div>

                <div class="flex flex-col text-center sm:text-left">
                    <h4 class="font-black text-slate-800 text-lg leading-tight">Persentase Kehadiran</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Tingkat kehadiran kumulatif siswa:</p>
                    <span class="inline-block self-center sm:self-start text-[11px] font-extrabold px-3 py-0.5 rounded-full mt-2 border {{ $attendBadgeColor }}">
                        {{ $attendLabel }}
                    </span>
                </div>
            </div>

            {{-- Bagian Kanan: Quick Glance Stats Grid --}}
            <div class="flex-1 w-full grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                    <span class="block text-2xl font-black text-emerald-600 leading-none">{{ $hadir }}</span>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Hadir</span>
                </div>
                <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                    <span class="block text-2xl font-black text-amber-500 leading-none">{{ $izin }}</span>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Izin</span>
                </div>
                <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                    <span class="block text-2xl font-black text-rose-500 leading-none">{{ $sakit }}</span>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Sakit</span>
                </div>
                <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                    <span class="block text-2xl font-black text-slate-500 leading-none">{{ $alpha }}</span>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Alpha</span>
                </div>
            </div>
        </div>

        {{-- Section: Detail Progress Bar --}}
        <div class="space-y-4 pt-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center shrink-0 border border-blue-100">
                    <i class="fa-solid fa-chart-simple text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Rincian Status Absensi</h4>
                    <p class="text-[11px] text-slate-400">Komparasi visual dari akumulasi data ketidakhadiran</p>
                </div>
            </div>

            {{-- Card Detail Lists dengan Progress Bar --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-5">
                {{-- Hadir --}}
                <div>
                    <div class="flex justify-between items-center text-sm font-semibold text-slate-700 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#22C55E]"></span>
                            <span class="font-bold text-slate-800">Hadir</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-400 font-normal">{{ $hadir }} Hari</span>
                            <span class="text-xs font-black text-emerald-500 w-12 text-right">{{ $hadirPct }}%</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full border border-slate-100 overflow-hidden">
                        <div class="bg-[#22C55E] h-full rounded-full transition-all duration-500" style="width: {{ $hadirPct }}%"></div>
                    </div>
                </div>

                {{-- Izin --}}
                <div>
                    <div class="flex justify-between items-center text-sm font-semibold text-slate-700 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
                            <span class="font-bold text-slate-800">Izin</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-400 font-normal">{{ $izin }} Hari</span>
                            <span class="text-xs font-black text-amber-500 w-12 text-right">{{ $izinPct }}%</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full border border-slate-100 overflow-hidden">
                        <div class="bg-[#F59E0B] h-full rounded-full transition-all duration-500" style="width: {{ $izinPct }}%"></div>
                    </div>
                </div>

                {{-- Sakit --}}
                <div>
                    <div class="flex justify-between items-center text-sm font-semibold text-slate-700 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#EF4444]"></span>
                            <span class="font-bold text-slate-800">Sakit</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-400 font-normal">{{ $sakit }} Hari</span>
                            <span class="text-xs font-black text-rose-500 w-12 text-right">{{ $sakitPct }}%</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full border border-slate-100 overflow-hidden">
                        <div class="bg-[#EF4444] h-full rounded-full transition-all duration-500" style="width: {{ $sakitPct }}%"></div>
                    </div>
                </div>

                {{-- Alpha --}}
                <div>
                    <div class="flex justify-between items-center text-sm font-semibold text-slate-700 mb-2">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#94A3B8]"></span>
                            <span class="font-bold text-slate-800">Alpha</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-400 font-normal">{{ $alpha }} Hari</span>
                            <span class="text-xs font-black text-slate-400 w-12 text-right">{{ $alphaPct }}%</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-50 h-2 rounded-full border border-slate-100 overflow-hidden">
                        <div class="bg-[#94A3B8] h-full rounded-full transition-all duration-500" style="width: {{ $alphaPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Informasi Tambahan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-4 shadow-sm">
                <div class="w-11 h-11 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shrink-0 border border-blue-100">
                    <i class="fa-solid fa-calendar-days text-base"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Hari Terhitung</p>
                    <p class="text-sm font-black text-slate-800">
                        {{ $total }} Hari <span class="text-[11px] font-normal text-slate-400">(Semester Ini)</span>
                    </p>
                </div>
            </div>

            <div class="bg-[#FFFDF5] rounded-2xl border border-[#FEF3C7] p-4 flex items-start gap-3.5 shadow-sm">
                <div class="w-10 h-10 bg-[#FFF9E6] text-[#D97706] rounded-xl flex items-center justify-center shrink-0 border border-[#FDE68A]/50">
                    <i class="fa-regular fa-lightbulb text-base"></i>
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                    Persentase kehadiran dihitung secara otomatis berdasarkan akumulasi total hari efektif sekolah berjalan pada semester ini.
                </p>
            </div>
        </div>

    @endif
</div>

@endsection
