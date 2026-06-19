@extends('layouts.dashboard')

@section('title', 'Input Nilai – ' . $classroom->name . ' · ' . $assignment->subject->name)

@section('back_url', route('teacher.kelas_saya.index'))

@section('content')

{{-- ─── Hero Banner ─── --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">

        {{-- Background Geometric Decoration --}}
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Input Nilai</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard text-base text-secondary opacity-95"></i>
                        <span>{{ $classroom->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-book text-base text-secondary opacity-95"></i>
                        <span>{{ $assignment->subject->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-base text-secondary opacity-95"></i>
                        <span>{{ $assignment->academicYear->year ?? '-' }} – Sem. {{ ucfirst($assignment->academicYear->semester ?? '') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Waves --}}
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10"
             style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10"
             style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6">

    {{-- ─── Back Button Card (matches parent/create style) ─── --}}
    <div class="-mt-16 mb-6 relative z-20 px-4 md:px-0">
        <a href="{{ route('teacher.kelas_saya.index') }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Kelas Saya</span>
            </div>
        </a>
    </div>

    {{-- ─── Summary Cards ─── --}}
    <div class="px-4 md:px-0">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php
                $types = [
                    ['label'=>'Total UH',    'icon'=>'fa-file-pen',   'color'=>'blue',   'count'=> $uhAssessments->count()],
                    ['label'=>'Total Tugas', 'icon'=>'fa-clipboard',  'color'=>'indigo', 'count'=> $tugasAssessments->count()],
                    ['label'=>'Total PAS',   'icon'=>'fa-star',       'color'=>'amber',  'count'=> $pasAssessments->count()],
                    ['label'=>'Total Siswa', 'icon'=>'fa-users',      'color'=>'emerald','count'=> $students->count()],
                ];
            @endphp
            @foreach($types as $t)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 md:p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $t['color'] }}-50 text-{{ $t['color'] }}-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $t['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">{{ $t['label'] }}</span>
                        <span class="text-xl font-black text-slate-800 leading-none mt-1 block">{{ $t['count'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ─── Main Content ─── --}}
    <div class="px-4 md:px-0 pb-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div id="flash-success" class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-semibold shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                {{ session('success') }}
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- Section Title --}}
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-table-list text-primary"></i>
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Formulir Penilaian Siswa</h4>
        </div>

        {{-- ─── Grade Form ─── --}}
        <form id="grade-form" method="POST" action="{{ route('teacher.grades.update', $assignment->id) }}">
            @csrf

            @if($assessments->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Belum Ada Penilaian</h3>
                    <p class="text-sm text-slate-500 mt-2">Belum ada assessment yang dibuat untuk kelas ini.</p>
                </div>
            @else

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                {{-- Scrollable Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-separate border-spacing-0" id="grade-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="sticky left-0 z-20 bg-slate-800 text-white text-left px-4 py-3 font-black text-xs uppercase tracking-wider border-r border-slate-700 min-w-[200px] shadow-[2px_0_8px_rgba(0,0,0,0.15)]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-user-graduate text-secondary"></i> Data Siswa
                                    </div>
                                </th>
                                @if($uhAssessments->count())
                                <th colspan="{{ $uhAssessments->count() }}" class="bg-blue-600 text-white text-center px-3 py-2 text-[11px] font-black uppercase tracking-widest border-b border-blue-700">
                                    <i class="fa-solid fa-file-pen mr-1"></i> Ulangan Harian
                                </th>
                                @endif
                                @if($tugasAssessments->count())
                                <th colspan="{{ $tugasAssessments->count() }}" class="bg-indigo-600 text-white text-center px-3 py-2 text-[11px] font-black uppercase tracking-widest border-b border-indigo-700">
                                    <i class="fa-solid fa-clipboard mr-1"></i> Tugas
                                </th>
                                @endif
                                @if($pasAssessments->count())
                                <th colspan="{{ $pasAssessments->count() }}" class="bg-amber-500 text-white text-center px-3 py-2 text-[11px] font-black uppercase tracking-widest border-b border-amber-600">
                                    <i class="fa-solid fa-star mr-1"></i> PAS
                                </th>
                                @endif
                                <th class="bg-emerald-600 text-white text-center px-3 py-2 text-[11px] font-black uppercase tracking-widest border-b border-emerald-700 min-w-[90px]">
                                    <i class="fa-solid fa-calculator mr-1"></i> Nilai Akhir
                                </th>
                            </tr>
                            <tr>
                                @foreach($uhAssessments as $a)
                                <th class="bg-blue-50 text-blue-700 text-center px-2 py-2 text-[11px] font-bold border-r border-blue-100 min-w-[80px]">
                                    {{ $a->name }}<br><span class="font-normal text-blue-400">({{ $a->weight }}%)</span>
                                </th>
                                @endforeach
                                @foreach($tugasAssessments as $a)
                                <th class="bg-indigo-50 text-indigo-700 text-center px-2 py-2 text-[11px] font-bold border-r border-indigo-100 min-w-[80px]">
                                    {{ $a->name }}<br><span class="font-normal text-indigo-400">({{ $a->weight }}%)</span>
                                </th>
                                @endforeach
                                @foreach($pasAssessments as $a)
                                <th class="bg-amber-50 text-amber-700 text-center px-2 py-2 text-[11px] font-bold border-r border-amber-100 min-w-[80px]">
                                    {{ $a->name }}<br><span class="font-normal text-amber-400">({{ $a->weight }}%)</span>
                                </th>
                                @endforeach
                                <th class="bg-emerald-50 text-emerald-700 text-center px-2 py-2 text-[11px] font-bold min-w-[90px]">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/60' }} hover:bg-blue-50/40 transition-colors group" data-student-id="{{ $student->id }}">
                                <td class="sticky left-0 z-10 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }} group-hover:bg-blue-50/60 px-4 py-2.5 border-r border-slate-100 shadow-[2px_0_8px_rgba(0,0,0,0.04)] transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary text-[11px] font-black flex items-center justify-center shrink-0">{{ $index + 1 }}</div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 text-xs leading-tight truncate group-hover:text-primary transition-colors">{{ $student->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">NISN: {{ $student->nisn }}</p>
                                        </div>
                                    </div>
                                </td>
                                @foreach($uhAssessments as $a)
                                @php $ge = $gradeEntriesMap["{$a->id}_{$student->id}"] ?? null; @endphp
                                <td class="px-2 py-2 border-r border-slate-100 text-center">
                                    <input type="number" name="scores[{{ $a->id }}][{{ $student->id }}]"
                                           value="{{ $ge?->score !== null ? (float)$ge->score : '' }}"
                                           min="0" max="100" step="0.01" placeholder="–"
                                           class="score-input w-16 text-center text-sm font-semibold bg-blue-50 border border-blue-200 rounded-lg px-1 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all hover:border-blue-300"
                                           data-student="{{ $student->id }}" data-assessment="{{ $a->id }}" data-weight="{{ $a->weight }}">
                                </td>
                                @endforeach
                                @foreach($tugasAssessments as $a)
                                @php $ge = $gradeEntriesMap["{$a->id}_{$student->id}"] ?? null; @endphp
                                <td class="px-2 py-2 border-r border-slate-100 text-center">
                                    <input type="number" name="scores[{{ $a->id }}][{{ $student->id }}]"
                                           value="{{ $ge?->score !== null ? (float)$ge->score : '' }}"
                                           min="0" max="100" step="0.01" placeholder="–"
                                           class="score-input w-16 text-center text-sm font-semibold bg-indigo-50 border border-indigo-200 rounded-lg px-1 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all hover:border-indigo-300"
                                           data-student="{{ $student->id }}" data-assessment="{{ $a->id }}" data-weight="{{ $a->weight }}">
                                </td>
                                @endforeach
                                @foreach($pasAssessments as $a)
                                @php $ge = $gradeEntriesMap["{$a->id}_{$student->id}"] ?? null; @endphp
                                <td class="px-2 py-2 border-r border-slate-100 text-center">
                                    <input type="number" name="scores[{{ $a->id }}][{{ $student->id }}]"
                                           value="{{ $ge?->score !== null ? (float)$ge->score : '' }}"
                                           min="0" max="100" step="0.01" placeholder="–"
                                           class="score-input w-16 text-center text-sm font-semibold bg-amber-50 border border-amber-200 rounded-lg px-1 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all hover:border-amber-300"
                                           data-student="{{ $student->id }}" data-assessment="{{ $a->id }}" data-weight="{{ $a->weight }}">
                                </td>
                                @endforeach
                                <td class="px-2 py-2 text-center">
                                    <span class="final-score inline-block min-w-[52px] text-center text-sm font-black bg-emerald-100 text-emerald-700 rounded-lg px-2 py-1.5"
                                          data-student="{{ $student->id }}">–</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Desktop footer save bar (hidden on mobile – bottom_bar handles it) --}}
                <div class="hidden md:flex px-5 py-4 border-t border-slate-100 bg-slate-50 items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 font-medium">
                        <i class="fa-solid fa-info-circle text-blue-400 mr-1"></i>
                        Nilai Akhir dihitung otomatis berdasarkan bobot persentase.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('teacher.kelas_saya.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 font-bold text-xs uppercase tracking-wider transition-all">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" id="save-btn"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-primary/20 hover:shadow-lg active:scale-95">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span id="save-btn-text">Simpan Semua Nilai</span>
                        </button>
                    </div>
                </div>
            </div>

            @endif
        </form>

    </div>
</div>

@endsection

{{-- ─── Mobile Bottom Action Bar (replaces default bottom nav) ─── --}}
@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.10)]">
    <div class="flex items-center gap-3 px-4 py-3">

        {{-- Back button --}}
        <a href="{{ route('teacher.kelas_saya.index') }}"
           class="flex-1 flex items-center justify-center gap-2 h-12 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-wider transition-all active:scale-95">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span>Kembali</span>
        </a>

        {{-- Save button --}}
        <button form="grade-form" type="submit" id="save-btn-mobile"
                class="flex-[2] flex items-center justify-center gap-2 h-12 rounded-xl bg-primary hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-primary/25 active:scale-95">
            <i class="fa-solid fa-floppy-disk text-sm"></i>
            <span id="save-btn-mobile-text">Simpan Semua Nilai</span>
        </button>

    </div>
    {{-- Safe area for devices with home indicator --}}
    <div class="h-safe-area-inset-bottom bg-white"></div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const inputs = document.querySelectorAll('.score-input');

    function computeFinalForStudent(studentId) {
        const studentInputs = document.querySelectorAll(`.score-input[data-student="${studentId}"]`);
        let weightedSum = 0;
        let totalWeight = 0;
        let hasAnyValue = false;

        studentInputs.forEach(inp => {
            const val = parseFloat(inp.value);
            const weight = parseFloat(inp.dataset.weight);
            if (!isNaN(val) && !isNaN(weight)) {
                weightedSum += val * weight;
                totalWeight += weight;
                hasAnyValue = true;
            }
        });

        const display = document.querySelector(`.final-score[data-student="${studentId}"]`);
        if (!display) return;

        if (!hasAnyValue || totalWeight === 0) {
            display.textContent = '–';
            display.classList.remove('text-emerald-700', 'bg-emerald-100', 'text-rose-700', 'bg-rose-100', 'text-amber-700', 'bg-amber-100');
            display.classList.add('text-slate-400', 'bg-slate-100');
            return;
        }

        const finalScore = (weightedSum / 100).toFixed(2);
        display.textContent = finalScore;
        display.classList.remove('text-slate-400', 'bg-slate-100', 'text-rose-700', 'bg-rose-100', 'text-emerald-700', 'bg-emerald-100', 'text-amber-700', 'bg-amber-100');
        const num = parseFloat(finalScore);
        if (num >= 75)      display.classList.add('text-emerald-700', 'bg-emerald-100');
        else if (num >= 60) display.classList.add('text-amber-700',   'bg-amber-100');
        else                display.classList.add('text-rose-700',    'bg-rose-100');
    }

    // Compute on load
    const studentIds = new Set([...inputs].map(i => i.dataset.student));
    studentIds.forEach(id => computeFinalForStudent(id));

    // Recompute on change + Enter key navigation
    inputs.forEach(inp => {
        inp.addEventListener('input', () => computeFinalForStudent(inp.dataset.student));
        inp.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const allInputs = [...inputs];
                const idx = allInputs.indexOf(inp);
                if (idx < allInputs.length - 1) allInputs[idx + 1].focus();
            }
        });
    });

    // Loading state on submit (both desktop and mobile buttons)
    const form = document.getElementById('grade-form');
    if (form) {
        form.addEventListener('submit', () => {
            ['save-btn', 'save-btn-mobile'].forEach(id => {
                const btn = document.getElementById(id);
                if (btn) { btn.disabled = true; btn.classList.add('opacity-70'); }
            });
            const t1 = document.getElementById('save-btn-text');
            const t2 = document.getElementById('save-btn-mobile-text');
            if (t1) t1.textContent = 'Menyimpan…';
            if (t2) t2.textContent = 'Menyimpan…';
        });
    }
})();
</script>
@endpush
