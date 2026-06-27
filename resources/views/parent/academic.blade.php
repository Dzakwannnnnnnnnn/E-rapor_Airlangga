@extends('layouts.dashboard')

@section('title', 'Detail Akademik - e-Rapor')
@section('back_url', route('parent.dashboard'))

@section('content')

@php
    $studentName = $student ? $student->name : '';
    $avgScore    = isset($avgScore) ? $avgScore : null;

    $gradeLabel  = $avgScore !== null
        ? ($avgScore >= 85 ? 'Sangat Baik' : ($avgScore >= 75 ? 'Baik' : 'Perlu Perhatian'))
        : null;

    $colors = [
        ['bg-blue-500',    'text-white', 'border-l-blue-500',    'fa-cubes',     'bg-blue-50/50',    'text-blue-600'],
        ['bg-indigo-500',  'text-white', 'border-l-indigo-500',  'fa-language',  'bg-indigo-50/50',  'text-indigo-600'],
        ['bg-emerald-500', 'text-white', 'border-l-emerald-500', 'fa-code',      'bg-emerald-50/50', 'text-emerald-600'],
        ['bg-amber-500',   'text-white', 'border-l-amber-500',   'fa-atom',      'bg-amber-50/50',   'text-amber-600'],
        ['bg-rose-500',    'text-white', 'border-l-rose-500',    'fa-running',   'bg-rose-50/50',    'text-rose-600'],
        ['bg-cyan-500',    'text-white', 'border-l-cyan-500',    'fa-book-open', 'bg-cyan-50/50',    'text-cyan-600'],
    ];
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
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Detail Akademik</h3>
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

            {{-- Filter Dropdown Semester (Dinamis) --}}
            <div class="relative shrink-0 self-center md:self-start">
                <form method="GET" action="{{ route('parent.academic') }}" id="semester-filter-form">
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
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

{{-- Content Area --}}
<div class="max-w-6xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16 relative z-20">

    @if(!$student)
        <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center shadow-sm -mt-16">
            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-3xl mb-3 block"></i>
            <p class="text-sm font-bold text-slate-700">Siswa tidak ditemukan.</p>
        </div>
    @elseif($grades->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center shadow-sm -mt-16">
            <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-slate-300 text-2xl"></i>
            </div>
            <p class="text-sm font-bold text-slate-700 mb-1">Belum Ada Data Nilai</p>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">Nilai mata pelajaran akan tampil di sini setelah guru menginput nilai.</p>
        </div>
    @else

        {{-- Overlapping Card: Rata-rata Nilai & Bar Chart --}}
        @if($avgScore !== null)
            <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 flex flex-col lg:flex-row items-center justify-between gap-6 -mt-16">

                {{-- Info Ringkasan Kiri --}}
                <div class="flex items-center gap-5 shrink-0 w-full lg:w-auto border-b lg:border-b-0 lg:border-r border-slate-100 pb-4 lg:pb-0 lg:pr-8">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex flex-col items-center justify-center shrink-0 border border-emerald-100 shadow-inner">
                        <span class="text-2xl font-black leading-none">{{ number_format($avgScore, 0) }}</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider opacity-80 mt-1">Rata2</span>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="font-black text-slate-800 text-lg leading-tight">Performa Akademik</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Predikat kumulatif semester ini:</p>
                        <span class="inline-block self-start text-[11px] font-extrabold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full mt-1.5 border border-emerald-200/50">
                            {{ $gradeLabel }}
                        </span>
                    </div>
                </div>

                {{-- Grafik Batang --}}
                <div class="flex-1 w-full overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-100">
                    <div class="flex items-end justify-between lg:justify-start gap-4 lg:gap-6 h-28 pt-4 px-2 min-w-[380px]">
                        @foreach($grades as $grade)
                            @php
                                $score      = $grade->final_score;
                                $mapelName  = $grade->classroomSubjectTeacher->subject->name ?? 'MP';
                                $shortLabel = strtoupper(substr($mapelName, 0, 3));
                                $barColor   = $score >= 85 ? 'bg-emerald-500' : ($score >= 75 ? 'bg-blue-500' : 'bg-amber-500');
                            @endphp
                            <div class="flex flex-col items-center flex-1 lg:flex-none lg:w-12 group relative">
                                <span class="text-[10px] font-black text-slate-700 mb-1 group-hover:text-primary transition-colors">
                                    {{ round($score) }}
                                </span>
                                <div class="w-full lg:w-8 bg-slate-50 rounded-t-lg h-16 relative flex items-end overflow-hidden border border-slate-100">
                                    <div class="{{ $barColor }} w-full rounded-t-md transition-all duration-500 ease-out group-hover:opacity-85 shadow-sm"
                                         style="height: {{ $score }}%;"></div>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 mt-2 tracking-tight uppercase truncate max-w-[42px]" title="{{ $mapelName }}">
                                    {{ $shortLabel }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        @endif

        {{-- Section: Daftar Mata Pelajaran --}}
        <div class="space-y-4 pt-2">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center shrink-0 border border-blue-100">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Mata Pelajaran</h4>
                    <p class="text-[11px] text-slate-400">Klik mapel untuk melihat detail komponen penilaian & tugas</p>
                </div>
            </div>

            {{-- Grid Daftar Mapel --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($grades as $index => $grade)
                    @php
                        $score = $grade->final_score;

                        if ($score >= 88) {
                            $sub_grade = 'A';
                            $sub_color = 'text-emerald-500 bg-emerald-50 border-emerald-100';
                        } elseif ($score >= 82) {
                            $sub_grade = 'A-';
                            $sub_color = 'text-emerald-600/90 bg-emerald-50/50 border-emerald-100/50';
                        } elseif ($score >= 78) {
                            $sub_grade = 'B+';
                            $sub_color = 'text-blue-600 bg-blue-50 border-blue-100';
                        } elseif ($score >= 75) {
                            $sub_grade = 'B';
                            $sub_color = 'text-blue-500 bg-blue-50/50 border-blue-100/50';
                        } else {
                            $sub_grade = 'C';
                            $sub_color = 'text-amber-500 bg-amber-50 border-amber-100';
                        }

                        $colorPair = $colors[$index % count($colors)];
                        $cstId     = $grade->classroom_subject_teacher_id;
                    @endphp

                    {{-- Card Menu Item – dibungkus <a> agar bisa diklik --}}
                    <a href="{{ route('parent.academic.subject', $cstId) }}"
                       class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 {{ $colorPair[2] }}">
                        <div class="flex items-center gap-4 min-w-0">
                            {{-- Icon Mapel --}}
                            <div class="w-12 h-12 rounded-xl {{ $colorPair[0] }} {{ $colorPair[1] }} flex items-center justify-center shrink-0 transition-transform group-hover:scale-105 shadow-sm">
                                <i class="fa-solid {{ $colorPair[3] }} text-lg"></i>
                            </div>
                            {{-- Info Teks --}}
                            <div class="flex flex-col min-w-0">
                                <span class="font-bold text-slate-800 text-sm group-hover:text-primary transition-colors truncate leading-tight">
                                    {{ $grade->classroomSubjectTeacher->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                <span class="text-[11px] text-slate-400 mt-1 truncate">
                                    {{ $grade->classroomSubjectTeacher->teacher->user->name ?? '—' }}
                                </span>
                            </div>
                        </div>

                        {{-- Score Badge Kanan --}}
                        <div class="flex items-center gap-3 shrink-0 pl-2">
                            <div class="w-11 h-11 rounded-xl {{ $sub_color }} border flex flex-col items-center justify-center">
                                <span class="text-sm font-black leading-none text-slate-800">{{ round($score) }}</span>
                                <span class="text-[9px] font-bold opacity-75 mt-0.5">{{ $sub_grade }}</span>
                            </div>
                            <div class="text-slate-300 group-hover:text-primary transition-colors">
                                <i class="fa-solid fa-chevron-right text-xs transition-transform group-hover:translate-x-0.5"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    @endif
</div>

@endsection
