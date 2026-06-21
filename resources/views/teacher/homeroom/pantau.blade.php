@extends('layouts.dashboard')

@section('title', 'Pantau Nilai – ' . $student->name . ' · e-Rapor')

@section('back_url', route('teacher.homeroom.index'))

@section('content')

{{-- ─── Hero Banner ─── --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Pantau Nilai Siswa</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user text-secondary"></i>
                        <span>Siswa: <strong>{{ $student->name }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-secondary"></i>
                        <span>NISN: <strong>{{ $student->nisn }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16">

    {{-- Navigation Back --}}
    <div class="-mt-16 relative z-20">
        <a href="{{ route('teacher.homeroom.index') }}"
           class="flex sm:inline-flex items-center gap-3 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase block">Kembali ke Rapor Kelas</span>
            </div>
        </a>
    </div>

    {{-- Subject Grades Monitoring Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Transkrip Nilai Sementara</h4>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">TA: {{ $activeYear->year }} (Sem. {{ ucfirst($activeYear->semester) }})</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-48">Mata Pelajaran / Guru</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">KKM</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-28">Nilai Rapor</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-28">Ketuntasan</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($assignments as $index => $a)
                    @php
                        $grade = $grades->get($a->id);
                        $finalScore = $grade ? $grade->final_score : null;
                        $kkm = $a->subject->kkm ?? 75;
                        
                        $scoreColor = ($finalScore !== null && $finalScore >= $kkm) ? 'text-emerald-700' : 'text-rose-700';
                        $scoreLabel = ($finalScore !== null && $finalScore >= $kkm) ? 'Tuntas' : ($finalScore !== null ? 'Remedial' : 'Belum Diisi');
                        $scoreBadge = ($finalScore !== null && $finalScore >= $kkm) ? 'text-emerald-700 bg-emerald-50 border-emerald-100' : ($finalScore !== null ? 'text-rose-700 bg-rose-50 border-rose-100' : 'text-slate-400 bg-slate-50 border-slate-100');
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/20' }}">
                        <td class="px-4 py-4 text-[11px] font-bold text-slate-400">{{ $index + 1 }}</td>
                        
                        <td class="px-4 py-4">
                            <div class="font-bold text-slate-800 text-xs">{{ $a->subject->name }}</div>
                            <div class="text-[9px] font-semibold text-slate-400 mt-0.5">Guru: {{ $a->teacher->user->name }}</div>
                        </td>

                        <td class="px-4 py-4 text-center font-bold text-slate-600 text-xs">
                            {{ $kkm }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($finalScore !== null)
                                <span class="font-black text-sm {{ $scoreColor }}">{{ number_format($finalScore, 2) }}</span>
                            @else
                                <span class="text-slate-300 font-bold">–</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase {{ $scoreBadge }}">
                                {{ $scoreLabel }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-xs text-slate-600 font-medium leading-relaxed italic">
                            {{ $grade ? ($grade->description ?: 'Belum diisi.') : 'Menunggu input guru mapel.' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
