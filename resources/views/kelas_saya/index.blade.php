@extends('layouts.dashboard')

@section('title', 'Kelas Saya - e-Rapor')

@section('content')

<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Kelas Saya</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-user text-base text-secondary opacity-95"></i>
                            <span>Daftar kelas dan mata pelajaran yang Anda ampu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16">

    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 flex flex-col md:flex-row items-center justify-between gap-6 -mt-16 relative z-20">
        <div class="flex items-center gap-4 flex-1">
            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-md">
                <i class="fa-solid fa-calendar-day text-xl text-secondary"></i>
            </div>
            <div class="flex flex-col">
                <h4 class="font-extrabold text-slate-800 text-base leading-tight">Tahun Ajaran Aktif</h4>
                <p class="text-xs text-slate-500 mt-1">Periode akademik yang sedang berjalan saat ini.</p>
            </div>
        </div>

        <div class="hidden md:block w-px h-12 bg-slate-100"></div>

        <div class="flex items-center gap-4 shrink-0 w-full md:w-auto justify-between md:justify-start pt-4 md:pt-0 border-t border-dashed border-slate-100 md:border-t-0">
            <div class="w-11 h-11 rounded-xl {{ $activeYear ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center shrink-0 border border-slate-100 transition-colors">
                <i class="fa-solid {{ $activeYear ? 'fa-check-circle' : 'fa-triangle-exclamation' }} text-sm"></i>
            </div>

            <div class="flex flex-col text-left">
                <span class="text-sm font-black {{ $activeYear ? 'text-slate-800' : 'text-rose-600' }} uppercase tracking-wide leading-none">
                    @if($activeYear)
                        {{ $activeYear->year }}
                    @else
                        Tidak Ada
                    @endif
                </span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 block">
                    @if($activeYear)
                        SEMESTER {{ strtoupper($activeYear->semester) }}
                    @else
                        HUBUNGI ADMIN
                    @endif
                </span>
            </div>
        </div>
    </div>

    @if(!$activeYear)
        <div class="bg-white border border-rose-100 rounded-2xl p-8 text-center shadow-sm max-w-lg mx-auto mt-8">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Tahun Ajaran Tidak Aktif</h3>
            <p class="text-sm text-slate-500 mt-2 font-medium">
                Sistem mendeteksi bahwa saat ini tidak ada periode tahun ajaran yang sedang aktif. Silakan hubungi admin sekolah untuk mengaktifkan tahun ajaran saat ini agar Anda dapat mengakses kelas Anda.
            </p>
            <a href="/dashboard" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    @elseif($groupedAssignments->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm max-w-lg mx-auto mt-8">
            <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                <i class="fa-solid fa-chalkboard-user text-2xl animate-pulse"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Belum Ada Kelas</h3>
            <p class="text-sm text-slate-500 mt-2 font-medium">
                Anda belum ditugaskan untuk mengajar mata pelajaran apa pun pada tahun ajaran <strong>{{ $activeYear->year }} (Semester {{ ucfirst($activeYear->semester) }})</strong>.
            </p>
            <p class="text-[11px] text-slate-400 mt-1 font-semibold uppercase">
                Hubungi administrator untuk pengisian pembagian mengajar.
            </p>
            <a href="/dashboard" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-blue-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-sm">
                <i class="fa-solid fa-house"></i> Dashboard Utama
            </a>
        </div>
    @else
        <div class="space-y-10">
            @foreach($groupedAssignments as $subjectName => $assignments)
                @php
                    $subjectType = $assignments->first()->subject->type;
                    $isAcademic = $subjectType === 'academic';

                    // Konfigurasi Tema Warna Berdasarkan Tipe Mapel
                    $iconTheme = $isAcademic ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100';
                    $borderTheme = $isAcademic ? 'border-l-emerald-500' : 'border-l-indigo-500';
                    $cardIconTheme = $isAcademic ? 'bg-emerald-50 text-emerald-600' : 'bg-indigo-50 text-indigo-600';
                @endphp

                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $iconTheme }} flex items-center justify-center shrink-0 border">
                            <i class="fa-solid {{ $isAcademic ? 'fa-book' : 'fa-award' }} text-sm"></i>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">{{ $subjectName }}</h4>
                            <p class="text-[11px] text-slate-400 font-bold tracking-widest uppercase">
                                {{ $isAcademic ? 'Intrakurikuler' : 'Ekstrakurikuler' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($assignments as $assignment)
                            @php
                                $classroom = $assignment->classroom;
                                $studentCount = $classroom->students->count();
                            @endphp

                            <a href="#" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 {{ $borderTheme }}">
                                <div class="flex items-center gap-4 overflow-hidden">
                                    <div class="w-12 h-12 rounded-xl {{ $cardIconTheme }} flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                                        <i class="fa-solid fa-users text-lg"></i>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors truncate">
                                            {{ $classroom->name }}
                                        </span>
                                        <div class="flex items-center text-xs text-slate-500 mt-0.5 gap-1.5">
                                            <span class="truncate max-w-[100px] sm:max-w-[120px]" title="{{ $classroom->major }}">
                                                {{ $classroom->major }}
                                            </span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300 shrink-0"></span>
                                            <span class="shrink-0 font-medium text-slate-600">{{ $studentCount }} Siswa</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-slate-300 group-hover:text-primary transition-colors ml-2 shrink-0">
                                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
