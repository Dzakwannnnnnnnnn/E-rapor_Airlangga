@extends('layouts.dashboard')
@section('title', 'Dashboard Kepala Sekolah · e-Rapor')
@section('content')

{{-- Hero Header --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-20 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-secondary/20 border-4 border-secondary/40 flex items-center justify-center shrink-0 shadow-lg">
                    <i class="fa-solid fa-user-tie text-secondary text-3xl md:text-4xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2 uppercase tracking-wider shadow-sm">
                        <i class="fa-solid fa-building-columns text-[9px]"></i>
                        Kepala Sekolah
                    </div>
                    <h3 class="font-extrabold text-2xl md:text-3xl leading-tight uppercase tracking-wide">Dashboard Utama</h3>
                    <p class="text-white/70 text-xs md:text-sm font-medium mt-1.5">
                        Ringkasan kondisi akademik sekolah &mdash; <strong class="text-secondary">{{ $activeYear ? ucfirst($activeYear->semester) . ' ' . $activeYear->year : 'Tahun Ajaran Tidak Aktif' }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto -mt-14 space-y-6 px-4 md:px-0 pb-16 relative z-20">

    {{-- Stat Cards Global --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Total Siswa --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-primary text-sm"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Siswa</span>
            </div>
            <p class="text-3xl font-black text-primary tracking-tight leading-none">{{ $totalStudents }}</p>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-2">Total Peserta Didik</p>
        </div>

        {{-- Total Guru --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-blue-600 text-sm"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Guru</span>
            </div>
            <p class="text-3xl font-black text-blue-600 tracking-tight leading-none">{{ $totalTeachers }}</p>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-2">Tenaga Pengajar</p>
        </div>

        {{-- Total Kelas --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-school text-violet-600 text-sm"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kelas</span>
            </div>
            <p class="text-3xl font-black text-violet-600 tracking-tight leading-none">{{ $totalClasses }}</p>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-2">Rombongan Belajar</p>
        </div>

        {{-- Rapor Menunggu --}}
        <div class="bg-white rounded-2xl p-5 border {{ $pendingReports > 0 ? 'border-amber-200' : 'border-slate-100' }} shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 {{ $pendingReports > 0 ? 'bg-amber-50' : 'bg-emerald-50' }} rounded-xl flex items-center justify-center">
                    <i class="fa-solid {{ $pendingReports > 0 ? 'fa-hourglass-half text-amber-500' : 'fa-check-circle text-emerald-600' }} text-sm"></i>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Rapor</span>
            </div>
            <p class="text-3xl font-black {{ $pendingReports > 0 ? 'text-amber-500' : 'text-emerald-600' }} tracking-tight leading-none">{{ $pendingReports }}</p>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-2">
                {{ $pendingReports > 0 ? 'Menunggu Pengesahan' : 'Semua Tersahkan' }}
            </p>
        </div>
    </div>

    {{-- Progress Rapor Banner --}}
    @if($activeYear)
    <div class="bg-gradient-to-br from-primary to-blue-800 rounded-2xl p-6 shadow-lg relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 text-white/5 text-[8rem] pointer-events-none leading-none">
            <i class="fa-solid fa-file-circle-check"></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <p class="text-secondary text-[10px] font-black uppercase tracking-widest mb-1">Progress Pengesahan Rapor</p>
                    <h4 class="text-white font-black text-xl uppercase tracking-tight">
                        {{ $validatedReports }} / {{ $totalStudents }} Rapor Disahkan
                    </h4>
                    <p class="text-white/60 text-xs mt-1">
                        {{ $submittedReports }} diajukan wali kelas &middot; {{ $pendingReports }} menunggu tanda tangan
                    </p>
                </div>
                <a href="{{ route('headmaster.pengesahan.index') }}"
                   class="shrink-0 self-center bg-secondary hover:bg-yellow-400 text-primary font-black text-xs uppercase tracking-widest px-5 py-3 rounded-xl transition-all shadow-lg hover:scale-105 flex items-center gap-2">
                    <i class="fa-solid fa-stamp"></i>
                    Buka Pengesahan
                </a>
            </div>
            @php
                $progressPct = $totalStudents > 0 ? round(($validatedReports / $totalStudents) * 100) : 0;
            @endphp
            <div class="mt-5">
                <div class="flex justify-between text-[10px] font-bold text-white/70 mb-1.5">
                    <span>Progres Pengesahan</span>
                    <span>{{ $progressPct }}%</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-secondary h-full rounded-full transition-all duration-700"
                         style="width: {{ $progressPct }}%"></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Ringkasan per kelas --}}
    <div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center border border-primary/10">
                <i class="fa-solid fa-layer-group text-sm"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Status Per Kelas</h4>
                <p class="text-[11px] text-slate-400">Ringkasan progres rapor masing-masing kelas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($classroomsStats as $stat)
            @php
                $pct = $stat['total'] > 0 ? round(($stat['validated'] / $stat['total']) * 100) : 0;
            @endphp
            <div class="bg-white rounded-2xl border {{ $stat['pending'] > 0 ? 'border-amber-100' : 'border-slate-100' }} shadow-sm p-5 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h5 class="font-black text-slate-800 text-sm">{{ $stat['classroom']->name }}</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">{{ $stat['total'] }} Siswa</p>
                    </div>
                    @if($stat['pending'] > 0)
                        <span class="text-[9px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full uppercase">
                            {{ $stat['pending'] }} Pending
                        </span>
                    @elseif($pct === 100)
                        <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full uppercase">
                            <i class="fa-solid fa-check mr-0.5"></i> Selesai
                        </span>
                    @endif
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mb-3 overflow-hidden">
                    <div class="h-full rounded-full {{ $pct === 100 ? 'bg-emerald-500' : ($stat['pending'] > 0 ? 'bg-amber-400' : 'bg-primary') }} transition-all duration-500"
                         style="width: {{ $pct }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] font-bold text-slate-500">
                    <span>{{ $stat['validated'] }} disahkan</span>
                    <span class="font-black {{ $pct === 100 ? 'text-emerald-600' : 'text-primary' }}">{{ $pct }}%</span>
                </div>
            </div>
            @empty
            <div class="col-span-3 bg-slate-50 border border-slate-100 rounded-2xl p-8 text-center">
                <p class="text-sm text-slate-400 font-bold">Tidak ada data kelas.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Access --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('headmaster.akademik') }}"
           class="bg-white border border-slate-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors shrink-0">
                <i class="fa-solid fa-chart-bar text-lg"></i>
            </div>
            <div>
                <p class="font-black text-slate-800 text-sm uppercase tracking-tight">Akademik</p>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">Nilai per kelas & distribusi</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-200 text-xs ml-auto group-hover:text-primary group-hover:translate-x-0.5 transition-all"></i>
        </a>
        <a href="{{ route('headmaster.kehadiran') }}"
           class="bg-white border border-slate-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors shrink-0">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <p class="font-black text-slate-800 text-sm uppercase tracking-tight">Kehadiran</p>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">Rekap absensi semua kelas</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-200 text-xs ml-auto group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all"></i>
        </a>
        <a href="{{ route('headmaster.pengesahan.index') }}"
           class="bg-white border border-slate-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors shrink-0">
                <i class="fa-solid fa-stamp text-lg"></i>
            </div>
            <div>
                <p class="font-black text-slate-800 text-sm uppercase tracking-tight">Pengesahan</p>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">Sahkan rapor siswa</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-200 text-xs ml-auto group-hover:text-amber-500 group-hover:translate-x-0.5 transition-all"></i>
        </a>
    </div>
</div>
@endsection
