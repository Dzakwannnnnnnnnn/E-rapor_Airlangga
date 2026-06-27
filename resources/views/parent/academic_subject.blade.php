@extends('layouts.dashboard')

@section('title', ($cst->subject->name ?? 'Mata Pelajaran') . ' - Detail Akademik - e-Rapor')
@section('back_url', route('parent.academic'))

@section('content')

@php
    $subjectName  = $cst->subject->name ?? 'Mata Pelajaran';
    $teacherName  = $cst->teacher->user->name ?? '—';
    $semesterLabel = $cst->academicYear
        ? ucfirst($cst->academicYear->semester) . ' ' . $cst->academicYear->year
        : '—';

    $finalScore = $grade ? (float) $grade->final_score : null;
    $description = $grade ? $grade->description : null;

    if ($finalScore !== null) {
        if ($finalScore >= 88) { $gradeLabel = 'A'; $gradeColor = 'text-emerald-600 bg-emerald-50 border-emerald-200'; }
        elseif ($finalScore >= 82) { $gradeLabel = 'A-'; $gradeColor = 'text-emerald-600 bg-emerald-50 border-emerald-200'; }
        elseif ($finalScore >= 78) { $gradeLabel = 'B+'; $gradeColor = 'text-blue-600 bg-blue-50 border-blue-200'; }
        elseif ($finalScore >= 75) { $gradeLabel = 'B'; $gradeColor = 'text-blue-600 bg-blue-50 border-blue-200'; }
        else { $gradeLabel = 'C'; $gradeColor = 'text-amber-600 bg-amber-50 border-amber-200'; }
    } else {
        $gradeLabel = '—';
        $gradeColor = 'text-slate-400 bg-slate-50 border-slate-200';
    }

    // Kelompokkan assessment per tipe
    $assessmentGroups = $assessments->groupBy('type');
    $typeOrder = ['uh', 'tugas', 'pas'];
    $typeLabels = ['uh' => 'Ulangan Harian', 'tugas' => 'Tugas Harian', 'pas' => 'PAS / Ujian Akhir'];
    $typeIcons  = ['uh' => 'fa-pencil', 'tugas' => 'fa-clipboard-list', 'pas' => 'fa-file-alt'];
    $typeColors = [
        'uh'    => ['bg-blue-50',   'text-blue-600',   'border-blue-100',   'bg-blue-500'],
        'tugas' => ['bg-amber-50',  'text-amber-600',  'border-amber-100',  'bg-amber-500'],
        'pas'   => ['bg-rose-50',   'text-rose-600',   'border-rose-100',   'bg-rose-500'],
    ];

    // ─────────────────────────────────────────────────────────
    // PROSES DATA LINE CHART (TREN NILAI PER ASSESSMENT)
    // ─────────────────────────────────────────────────────────
    $chartData = [];
    foreach ($typeOrder as $type) {
        if (isset($assessmentGroups[$type]) && $assessmentGroups[$type]->isNotEmpty()) {
            foreach ($assessmentGroups[$type] as $assessment) {
                $entry = $assessment->gradeEntries->first();
                $entryScore = $entry ? (float) $entry->score : null;
                if ($entryScore !== null) {
                    $chartData[] = [
                        'name' => $assessment->name,
                        'score' => $entryScore
                    ];
                }
            }
        }
    }

    $hasChartData = count($chartData) > 0;
    $pathString = '';
    $areaString = '';
    $svgPoints = [];
    $chartWidth = 850; // Default min width untuk desktop

    if ($hasChartData) {
        $paddingLeft = 50;
        $paddingRight = 50;
        $widthPerItem = 120; // Jarak horizontal antar titik

        // Memastikan lebar chart mencakup seluruh container desktop tanpa sisa putih di kanan
        $calculatedWidth = ($widthPerItem * count($chartData)) + $paddingLeft + $paddingRight;
        $chartWidth = max($calculatedWidth, 850);
        $usableWidth = $chartWidth - $paddingLeft - $paddingRight;

        $stepX = count($chartData) > 1 ? $usableWidth / (count($chartData) - 1) : $usableWidth;

        foreach ($chartData as $idx => $item) {
            $x = $paddingLeft + ($idx * $stepX);

            // Skala Y di-zoom (Nilai 50 sampai 100). Rentang tinggi grafik = 130px (Y=40 ke Y=170)
            $safeScore = max($item['score'], 50);
            $y = 170 - (($safeScore - 50) / 50 * 130);

            $svgPoints[] = [
                'x' => $x,
                'y' => $y,
                'score' => round($item['score']),
                'name' => $item['name']
            ];
        }

        $coordPairs = [];
        foreach ($svgPoints as $p) {
            $coordPairs[] = $p['x'] . ',' . $p['y'];
        }
        $pathString = "M " . implode(" L ", $coordPairs);

        if (count($svgPoints) > 0) {
            $firstX = $svgPoints[0]['x'];
            $lastX = $svgPoints[count($svgPoints) - 1]['x'];
            $areaString = $pathString . " L $lastX,170 L $firstX,170 Z";
        }
    }

    // Helper untuk limit nama mapel di chart
    if (!function_exists('truncate_mapel_name_here')) {
        function truncate_mapel_name_here($string) {
            return strlen($string) > 12 ? substr($string, 0, 10) . '..' : $string;
        }
    }
@endphp

{{-- Hero Header --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-5">
                {{-- Subject Icon --}}
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center shrink-0 shadow-md">
                    <i class="fa-solid fa-book text-white text-3xl md:text-4xl"></i>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2 uppercase tracking-wider shadow-sm">
                        <i class="fa-solid fa-calendar-check text-[9px]"></i>
                        {{ $semesterLabel }}
                    </div>
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">{{ $subjectName }}</h3>
                    <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-x-5 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-teacher text-sm text-secondary opacity-95"></i>
                            <span>{{ $teacherName }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-user-graduate text-sm text-secondary opacity-95"></i>
                            <span>{{ $student->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nilai Akhir Badge --}}
            @if($finalScore !== null)
                <div class="shrink-0 self-center md:self-start bg-white/10 border border-white/20 rounded-2xl px-6 py-4 text-center backdrop-blur-sm">
                    <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Nilai Akhir</p>
                    <p class="text-4xl font-black text-white leading-none">{{ round($finalScore) }}</p>
                    <span class="inline-block mt-2 text-xs font-extrabold px-3 py-0.5 rounded-full bg-secondary/80 text-slate-900">
                        {{ $gradeLabel }}
                    </span>
                </div>
            @else
                <div class="shrink-0 self-center md:self-start bg-white/10 border border-white/20 rounded-2xl px-6 py-4 text-center backdrop-blur-sm">
                    <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest mb-1">Nilai Akhir</p>
                    <p class="text-3xl font-black text-white/40 leading-none">—</p>
                    <span class="inline-block mt-2 text-[10px] font-bold text-white/50">Belum ada</span>
                </div>
            @endif
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

{{-- Content --}}
<div class="max-w-5xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-20 relative z-20">

    {{-- Back Navigation (Style Full Width di Mobile seperti desain referensi) --}}
    <div class="-mt-12 md:-mt-14 mb-6 relative z-30">
        <a href="{{ route('parent.academic') }}"
           class="flex items-center w-full md:w-max md:inline-flex gap-4 bg-white rounded-2xl shadow-md border border-slate-100 p-3 md:pr-6 hover:bg-slate-50 transition-colors group">
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm md:text-base"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none block mb-1">Navigasi</span>
                <span class="text-xs md:text-sm font-black text-slate-800 uppercase leading-tight block">Daftar Mata Pelajaran</span>
            </div>
        </a>
    </div>

    {{-- ──────────────────────────────────── --}}
    {{-- NEW SECTION: Tren Perkembangan Nilai --}}
    {{-- ──────────────────────────────────── --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <i class="fa-solid fa-chart-line text-sm"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Tren Nilai Siswa</h4>
                <p class="text-[11px] text-slate-400">Grafik perkembangan nilai dari setiap assessment akademik</p>
            </div>
        </div>

        @if(!$hasChartData)
            <div class="bg-white rounded-2xl border border-slate-100 p-6 text-center shadow-sm text-slate-400 text-xs font-medium">
                 Belum ada tren nilai yang dapat dimuat.
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 md:p-6 overflow-hidden">
                {{-- Pembungkus Geser Horizontal Otomatis --}}
                <div class="overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                    {{-- Tinggi container ditambah untuk mengakomodasi text di bawah grafik --}}
                    <div style="min-width: {{ $chartWidth }}px; width: 100%;" class="h-64 relative select-none">
                        <svg class="w-full h-full" viewBox="0 0 {{ $chartWidth }} 240" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.00"/>
                                </linearGradient>
                            </defs>

                            {{-- Grid Y --}}
                            @foreach([100, 90, 80, 70, 60, 50] as $gridScore)
                                @php $gridY = 170 - (($gridScore - 50) / 50 * 130); @endphp
                                <line x1="45" y1="{{ $gridY }}" x2="{{ $chartWidth - 20 }}" y2="{{ $gridY }}" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4,4" />
                                <text x="15" y="{{ $gridY + 4 }}" fill="#94a3b8" class="text-[11px] font-extrabold font-sans">{{ $gridScore }}</text>
                            @endforeach

                            {{-- Chart Area & Line --}}
                            @if(count($svgPoints) > 1)
                                <path d="{{ $areaString }}" fill="url(#chartGradient)" />
                                <path d="{{ $pathString }}" fill="none" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            @else
                                <line x1="50" y1="{{ $svgPoints[0]['y'] }}" x2="100" y2="{{ $svgPoints[0]['y'] }}" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
                            @endif

                            {{-- Data Points & Labels --}}
                            @foreach($svgPoints as $pt)
                                {{-- Kotak Nilai --}}
                                <rect x="{{ $pt['x'] - 16 }}" y="{{ $pt['y'] - 24 }}" width="32" height="16" rx="4" fill="#1e293b" />
                                <text x="{{ $pt['x'] }}" y="{{ $pt['y'] - 13 }}" fill="#ffffff" text-anchor="middle" class="text-[10px] font-black font-sans">{{ $pt['score'] }}</text>

                                {{-- Lingkaran Titik --}}
                                <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="6" fill="#ffffff" stroke="#3b82f6" stroke-width="3" />

                                {{-- Label Nama Tugas/Ujian di Bawah (Y disesuaikan ke bawah batas grid) --}}
                                <text x="{{ $pt['x'] }}" y="200" fill="#475569" text-anchor="middle" class="text-[11px] font-bold font-sans uppercase tracking-tight">{{ truncate_mapel_name_here($pt['name']) }}</text>
                                <circle cx="{{ $pt['x'] }}" cy="186" r="2.5" fill="#cbd5e1" />
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ──────────────────────────────────── --}}
    {{-- SECTION 1: Komponen Penilaian        --}}
    {{-- ──────────────────────────────────── --}}
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center shrink-0 border border-blue-100">
                <i class="fa-solid fa-chart-bar text-sm"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Komponen Penilaian</h4>
                <p class="text-[11px] text-slate-400">Rincian nilai dari setiap komponen evaluasi</p>
            </div>
        </div>

        @if($assessments->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-clipboard text-slate-300 text-xl"></i>
                </div>
                <p class="text-sm font-bold text-slate-600 mb-1">Belum Ada Komponen Penilaian</p>
                <p class="text-xs text-slate-400">Guru belum menambahkan komponen penilaian untuk mata pelajaran ini.</p>
            </div>
        @else
            @foreach($typeOrder as $type)
                @if(isset($assessmentGroups[$type]) && $assessmentGroups[$type]->isNotEmpty())
                    @php
                        $group = $assessmentGroups[$type];
                        $tc = $typeColors[$type] ?? ['bg-slate-50', 'text-slate-600', 'border-slate-100', 'bg-slate-400'];
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        {{-- Group Header --}}
                        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-slate-100 {{ $tc[0] }}">
                            <div class="w-7 h-7 rounded-lg {{ $tc[3] }} text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid {{ $typeIcons[$type] ?? 'fa-file' }} text-xs"></i>
                            </div>
                            <div class="flex items-center justify-between flex-1">
                                <h5 class="font-extrabold text-sm {{ $tc[1] }} uppercase tracking-wide">
                                    {{ $typeLabels[$type] ?? ucfirst($type) }}
                                </h5>
                                <span class="text-xs font-bold {{ $tc[1] }} opacity-70">{{ $group->count() }} item</span>
                            </div>
                        </div>

                        {{-- Assessment Items --}}
                        <div class="divide-y divide-slate-50">
                            @foreach($group as $assessment)
                                @php
                                    $entry      = $assessment->gradeEntries->first();
                                    $entryScore = $entry ? (float) $entry->score : null;
                                    $weight     = $assessment->weight ?? 0;

                                    if ($entryScore === null) {
                                        $badgeClass = 'text-slate-400 bg-slate-50 border-slate-200';
                                        $scoreDisplay = '—';
                                    } elseif ($entryScore >= 85) {
                                        $badgeClass = 'text-emerald-600 bg-emerald-50 border-emerald-200';
                                        $scoreDisplay = number_format($entryScore, 0);
                                    } elseif ($entryScore >= 75) {
                                        $badgeClass = 'text-blue-600 bg-blue-50 border-blue-200';
                                        $scoreDisplay = number_format($entryScore, 0);
                                    } else {
                                        $badgeClass = 'text-amber-600 bg-amber-50 border-amber-200';
                                        $scoreDisplay = number_format($entryScore, 0);
                                    }
                                @endphp
                                <div class="flex items-center justify-between px-5 py-4 hover:bg-slate-50/50 transition-colors">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg {{ $tc[0] }} {{ $tc[1] }} {{ $tc[2] }} border flex items-center justify-center shrink-0 text-xs font-black">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-800 truncate leading-tight">{{ $assessment->name }}</p>
                                            <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                                                @if($assessment->date)
                                                    <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                        <i class="fa-regular fa-calendar text-[9px]"></i>
                                                        {{ $assessment->date->format('d M Y') }}
                                                    </span>
                                                @endif
                                                @if($weight > 0)
                                                    <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                        <i class="fa-solid fa-weight-hanging text-[9px]"></i>
                                                        Bobot {{ number_format($weight, 0) }}%
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 pl-4 flex flex-col items-end gap-1">
                                        <div class="w-12 h-12 rounded-xl border {{ $badgeClass }} flex flex-col items-center justify-center text-center">
                                            <span class="text-sm font-black leading-none">{{ $scoreDisplay }}</span>
                                            @if($entryScore !== null)
                                                <span class="text-[9px] font-bold opacity-70 mt-0.5">/ 100</span>
                                            @else
                                                <span class="text-[8px] font-bold opacity-60 mt-0.5 leading-tight text-center px-0.5">Kosong</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- Catatan per entry jika ada --}}
                                @if($entry && $entry->description)
                                    <div class="px-5 pb-3 -mt-2">
                                        <p class="text-[11px] text-slate-500 italic bg-slate-50 rounded-lg px-3 py-2 border border-slate-100 leading-relaxed">
                                            "{{ $entry->description }}"
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Tipe lain yang tidak termasuk di typeOrder --}}
            @foreach($assessmentGroups as $type => $group)
                @if(!in_array($type, $typeOrder))
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-slate-100 bg-slate-50">
                            <div class="w-7 h-7 rounded-lg bg-slate-400 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="fa-solid fa-file text-xs"></i>
                            </div>
                            <h5 class="font-extrabold text-sm text-slate-600 uppercase tracking-wide">{{ ucfirst($type) }}</h5>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach($group as $assessment)
                                @php
                                    $entry = $assessment->gradeEntries->first();
                                    $entryScore = $entry ? (float) $entry->score : null;
                                    $scoreDisplay = $entryScore !== null ? number_format($entryScore, 0) : '—';
                                    $badgeClass = $entryScore === null ? 'text-slate-400 bg-slate-50 border-slate-200' : ($entryScore >= 75 ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : 'text-amber-600 bg-amber-50 border-amber-200');
                                @endphp
                                <div class="flex items-center justify-between px-5 py-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $assessment->name }}</p>
                                        @if($assessment->date)
                                            <span class="text-[10px] text-slate-400">{{ $assessment->date->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                    <div class="w-12 h-12 rounded-xl border {{ $badgeClass }} flex flex-col items-center justify-center shrink-0 ml-4">
                                        <span class="text-sm font-black leading-none">{{ $scoreDisplay }}</span>
                                        @if($entryScore !== null)
                                            <span class="text-[9px] opacity-70 mt-0.5">/ 100</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    {{-- ──────────────────────────────────── --}}
    {{-- SECTION 3: Catatan Guru              --}}
    {{-- ──────────────────────────────────── --}}
    @if($description)
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 border border-violet-100">
                    <i class="fa-solid fa-comment-dots text-sm"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Catatan Guru</h4>
                    <p class="text-[11px] text-slate-400">Umpan balik & deskripsi dari guru pengampu</p>
                </div>
            </div>
            <div class="bg-violet-50/60 border border-violet-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start gap-3.5">
                    <div class="w-9 h-9 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-quote-left text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-700 leading-relaxed italic">{{ $description }}</p>
                        <p class="text-[10px] text-slate-400 mt-3 font-bold uppercase tracking-widest flex items-center gap-1.5">
                            <i class="fa-solid fa-chalkboard-teacher text-[9px]"></i>
                            {{ $teacherName }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-6 text-center shadow-sm">
            <i class="fa-regular fa-comment-dots text-slate-300 text-2xl mb-2 block"></i>
            <p class="text-xs font-bold text-slate-500">Belum ada catatan dari guru</p>
        </div>
    @endif

</div>
@endsection
