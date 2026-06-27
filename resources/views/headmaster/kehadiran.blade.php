@extends('layouts.dashboard')
@section('title', 'Kehadiran Sekolah · e-Rapor')
@section('content')

@php
    $allData    = $kehadiranStats->values();
    $totalHadir = $allData->sum('hadir');
    $totalSakit = $allData->sum('sakit');
    $totalIzin  = $allData->sum('izin');
    $totalAlpha = $allData->sum('alpha');
    $grandTotal = $totalHadir + $totalSakit + $totalIzin + $totalAlpha;

    // Perhitungan persentase presisi global
    $hadirPctGlobal = $grandTotal > 0 ? round(($totalHadir / $grandTotal) * 100, 1) : 0;
    $sakitPctGlobal = $grandTotal > 0 ? round(($totalSakit / $grandTotal) * 100, 1) : 0;
    $izinPctGlobal  = $grandTotal > 0 ? round(($totalIzin / $grandTotal) * 100, 1) : 0;
    $alphaPctGlobal = $grandTotal > 0 ? round(($totalAlpha / $grandTotal) * 100, 1) : 0;

    // Angka bulat di dalam Donut Chart Sekolah
    $donutPct = $grandTotal > 0 ? round(($totalHadir / $grandTotal) * 0, 0) : 0;
    $donutPct = round($hadirPctGlobal);

    // Logika Predikat Label Sekolah
    $schoolLabel = $hadirPctGlobal >= 95 ? 'Sangat Baik' : ($hadirPctGlobal >= 85 ? 'Baik' : 'Perlu Perhatian');
    $schoolBadgeColor = $hadirPctGlobal >= 95
        ? 'text-emerald-600 bg-emerald-50 border-emerald-200'
        : ($hadirPctGlobal >= 85 ? 'text-blue-600 bg-blue-50 border-blue-200' : 'text-amber-600 bg-amber-50 border-amber-200');

    $worstClass = $allData->first(); // Kelas dengan kehadiran terendah (karena sorted asc)
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
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Kehadiran Sekolah</h3>
                    <p class="text-white/70 text-xs md:text-sm mt-1.5">Rekapitulasi absensi seluruh kelas &mdash; mempermudah monitoring ketidakhadiran siswa</p>
                </div>
            </div>

            {{-- Semester Filter --}}
            <div class="shrink-0 self-center md:self-start">
                <form method="GET" action="{{ route('headmaster.kehadiran') }}" id="semester-filter-form">
                    <div class="relative">
                        <select name="academic_year_id" onchange="document.getElementById('semester-filter-form').submit()"
                            class="appearance-none bg-white/10 hover:bg-white/20 border border-white/20 shadow-sm rounded-xl pl-4 pr-10 py-2.5 text-xs font-bold text-white focus:outline-none cursor-pointer backdrop-blur-sm transition-all min-w-[180px]">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" class="text-slate-800 bg-white"
                                    {{ $selectedYear && $selectedYear->id == $ay->id ? 'selected' : '' }}>
                                    {{ ucfirst($ay->semester) }} {{ $ay->year }} {{ $ay->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

{{-- Content Area --}}
<div class="max-w-6xl mx-auto space-y-6 px-4 md:px-0 pb-16 relative z-20">

    {{-- Overlapping Premium Card: Ringkasan Global Sekolah & Donut Chart --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 flex flex-col md:flex-row items-center justify-between gap-8 -mt-16">

        {{-- Bagian Kiri: Donut Chart Rata-Rata Sekolah --}}
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
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Hadir</span>
                </div>
            </div>

            <div class="flex flex-col text-center sm:text-left">
                <h4 class="font-black text-slate-800 text-lg leading-tight">Rata-Rata Kehadiran</h4>
                <p class="text-xs text-slate-400 mt-0.5">Persentase kehadiran kumulatif sekolah</p>
                <span class="inline-block self-center sm:self-start text-[11px] font-extrabold px-3 py-0.5 rounded-full mt-2 border {{ $schoolBadgeColor }}">
                    {{ $schoolLabel }}
                </span>
            </div>
        </div>

        {{-- Bagian Kanan: Quick Glance Stats Grid (4 Status) --}}
        <div class="flex-1 w-full grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                <span class="block text-2xl font-black text-emerald-600 leading-none">{{ $totalHadir }}</span>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Hadir</span>
            </div>
            <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                <span class="block text-2xl font-black text-blue-600 leading-none">{{ $totalSakit }}</span>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Sakit ({{ $sakitPctGlobal }}%)</span>
            </div>
            <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                <span class="block text-2xl font-black text-amber-500 leading-none">{{ $totalIzin }}</span>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Izin ({{ $izinPctGlobal }}%)</span>
            </div>
            <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3.5 text-center">
                <span class="block text-2xl font-black text-rose-600 leading-none">{{ $totalAlpha }}</span>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1.5">Alpha ({{ $alphaPctGlobal }}%)</span>
            </div>
        </div>
    </div>

    {{-- Alert Kelas Absensi Terburuk (Gaya Desain Baru) --}}
    @if($worstClass && $worstClass['total'] > 0 && $worstClass['hadirPct'] < 85)
    <div class="bg-red-50/70 border border-red-200 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
        <div class="w-11 h-11 bg-red-100 text-red-600 rounded-xl flex items-center justify-center shrink-0 border border-red-200/50">
            <i class="fa-solid fa-triangle-exclamation text-base"></i>
        </div>
        <div>
            <p class="font-black text-red-700 text-sm uppercase tracking-wide">Perhatian: Kehadiran Kelas Rendah</p>
            <p class="text-red-600 text-xs mt-1 leading-relaxed">
                Kelas <strong class="text-red-800 font-extrabold">{{ $worstClass['classroom']->name }}</strong> mencatat persentase terendah sebesar <strong>{{ $worstClass['hadirPct'] }}%</strong> dengan total <strong>{{ $worstClass['alpha'] }} Alpha</strong>. Disarankan untuk berkoordinasi dengan Wali Kelas terkait.
            </p>
        </div>
    </div>
    @endif

    {{-- Tabel Rekap Utama Per Kelas --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-table-list text-sm"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Detail Absensi Per Kelas</h4>
                    <p class="text-[10px] text-slate-400 font-semibold">Diurutkan dari tingkat kehadiran yang paling rendah</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Kelas</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-emerald-600 uppercase tracking-widest w-24">Hadir</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-blue-600 uppercase tracking-widest w-24">Sakit</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-amber-600 uppercase tracking-widest w-24">Izin</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-rose-500 uppercase tracking-widest w-24">Alpha</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-48">Grafik Presentase</th>
                        <th class="text-center px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-32">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($kehadiranStats as $index => $stat)
                    @php
                        $pct = $stat['hadirPct'];
                        $barColor = $pct >= 95 ? 'bg-emerald-500' : ($pct >= 85 ? 'bg-blue-500' : 'bg-amber-500');
                        $pctColor = $pct >= 95 ? 'text-emerald-600' : ($pct >= 85 ? 'text-blue-600' : 'text-amber-600');

                        // Label status per baris kelas
                        $classLabel = $pct >= 95 ? 'Sangat Baik' : ($pct >= 85 ? 'Baik' : 'Perlu Atensi');
                        $classBadge = $pct >= 95
                            ? 'text-emerald-600 bg-emerald-50 border-emerald-100'
                            : ($pct >= 85 ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-amber-600 bg-amber-50 border-amber-100');

                        $rowAlert = $pct < 85;
                    @endphp
                    <tr class="{{ $rowAlert ? 'bg-amber-50/20' : ($index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30') }} hover:bg-blue-50/20 transition-colors">
                        <td class="px-5 py-4 text-[11px] font-black text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-4">
                            <span class="font-black text-slate-800 text-xs block">{{ $stat['classroom']->name }}</span>
                            <span class="text-[9px] text-slate-400 font-bold">Total Entri: {{ $stat['total'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-slate-700">{{ $stat['hadir'] }}</td>
                        <td class="px-4 py-4 text-center font-medium text-slate-600">{{ $stat['sakit'] }}</td>
                        <td class="px-4 py-4 text-center font-medium text-slate-600">{{ $stat['izin'] }}</td>
                        <td class="px-4 py-4 text-center font-bold {{ $stat['alpha'] > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            {{ $stat['alpha'] }}
                        </td>
                        {{-- Visual Progress Bar --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-slate-100 rounded-full h-2 border border-slate-100 overflow-hidden">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-[11px] font-black {{ $pctColor }} w-10 text-right shrink-0">{{ $pct }}%</span>
                            </div>
                        </td>
                        {{-- Status Badge --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-block text-[10px] font-black px-2.5 py-0.5 rounded-full border {{ $classBadge }}">
                                {{ $classLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400 text-xs font-bold">
                            Tidak ada data rekapitulasi kehadiran pada semester ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
