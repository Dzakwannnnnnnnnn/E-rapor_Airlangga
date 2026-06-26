@extends('layouts.dashboard')

@section('title', 'Akademik - e-Rapor')
@section('back_url', route('parent.dashboard'))

@section('content')

@php
    $studentName = $student ? $student->name : '';
    $avgScore    = isset($avgScore) ? $avgScore : null;
    $gradeLabel  = $avgScore !== null
        ? ($avgScore >= 88 ? 'Sangat Baik' : ($avgScore >= 75 ? 'Baik' : 'Perlu Perhatian'))
        : null;
@endphp

{{-- Header --}}
<div class="mb-6 flex items-start justify-between gap-4">
    <div>
        <h1 class="text-xl font-black text-text uppercase tracking-tight leading-none">Detail Nilai Akademik</h1>
        <p class="text-[11px] text-muted font-bold uppercase tracking-wider mt-1">
            Siswa: <span class="text-primary">{{ $studentName }}</span>
            @if($student && $student->classroom)
                &mdash; {{ $student->classroom->name }}
            @endif
        </p>
    </div>
    @if($avgScore !== null)
        <div class="shrink-0 text-right bg-primary/5 rounded-xl px-4 py-2 border border-primary/10">
            <p class="text-[9px] font-bold text-muted uppercase tracking-widest">Rata-rata</p>
            <p class="text-2xl font-black text-primary leading-none">{{ number_format($avgScore, 1) }}</p>
            <p class="text-[9px] font-bold {{ $gradeLabel === 'Sangat Baik' ? 'text-emerald-600' : ($gradeLabel === 'Baik' ? 'text-blue-600' : 'text-amber-600') }} uppercase tracking-widest mt-0.5">{{ $gradeLabel }}</p>
        </div>
    @endif
</div>

@if(!$student)
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-3xl mb-3 block"></i>
        <p class="text-sm font-black text-slate-700">Siswa tidak ditemukan.</p>
    </div>
@elseif($grades->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm">
        <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-book-open text-slate-300 text-2xl"></i>
        </div>
        <p class="text-sm font-black text-slate-700 mb-1">Belum Ada Data Nilai</p>
        <p class="text-xs text-slate-400 max-w-xs mx-auto">Nilai mata pelajaran akan tampil di sini setelah guru menginput dan mengunci nilai.</p>
    </div>
@else
    <div class="flex flex-col gap-3">
        @foreach($grades as $grade)
            @php
                $score   = $grade->final_score;
                $grade_label = $score >= 88 ? 'A' : ($score >= 75 ? 'B' : ($score >= 60 ? 'C' : 'D'));
                $grade_color = $score >= 88
                    ? 'text-emerald-600 bg-emerald-50 border-emerald-100'
                    : ($score >= 75 ? 'text-blue-600 bg-blue-50 border-blue-100'
                    : ($score >= 60 ? 'text-amber-600 bg-amber-50 border-amber-100'
                    : 'text-red-600 bg-red-50 border-red-100'));
                $barPct = min(100, round($score));
                $barColor = $score >= 88 ? 'bg-emerald-500' : ($score >= 75 ? 'bg-blue-500' : ($score >= 60 ? 'bg-amber-500' : 'bg-red-500'));
            @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md hover:border-primary/20 transition-all duration-200">
                <div class="flex items-start gap-4">
                    {{-- Score Badge --}}
                    <div class="shrink-0 w-14 h-14 rounded-xl {{ $grade_color }} border flex flex-col items-center justify-center">
                        <span class="text-xl font-black leading-none">{{ $grade_label }}</span>
                        <span class="text-[9px] font-bold opacity-70">Predikat</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <div class="min-w-0">
                                <h4 class="text-sm font-black text-slate-800 leading-tight truncate">
                                    {{ $grade->classroomSubjectTeacher->subject->name ?? 'Mata Pelajaran' }}
                                </h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                    Guru: {{ $grade->classroomSubjectTeacher->teacher->user->name ?? '—' }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-2xl font-black text-primary tracking-tight">{{ number_format($score, 1) }}</span>
                                <span class="block text-[9px] text-slate-400 font-bold uppercase">/ 100</span>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-3">
                            <div class="h-1.5 rounded-full {{ $barColor }} transition-all duration-700"
                                 style="width: {{ $barPct }}%"></div>
                        </div>

                        {{-- Deskripsi --}}
                        @if($grade->description)
                            <p class="text-[11px] text-slate-500 leading-relaxed bg-slate-50 rounded-lg px-3 py-2 italic border border-slate-100">
                                "{{ $grade->description }}"
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
