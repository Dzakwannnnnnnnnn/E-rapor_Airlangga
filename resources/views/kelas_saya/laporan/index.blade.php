@extends('layouts.dashboard')

@section('title', 'Laporan Rapor – e-Rapor')

@section('content')

{{-- ─── Hero Banner ─── --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)<div class="w-1.5 h-1.5 bg-white rounded-full"></div>@endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Laporan Rapor</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-base text-secondary opacity-95"></i>
                        <span>Kelola nilai akhir, predikat, serta deskripsi capaian siswa</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-20">

    {{-- ─── Tahun Ajaran Aktif (overlapping card) ─── --}}
    <div class="bg-[#f8faff] rounded-2xl shadow-sm border border-blue-100 p-5 md:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 -mt-20 relative z-20">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-md">
                <i class="fa-solid fa-calendar-check text-xl text-secondary"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Tahun Ajaran Aktif</p>
                <p class="text-xs text-slate-500 mt-0.5">Periode akademik yang sedang berjalan saat ini.</p>
            </div>
        </div>

        @if($activeYear)
            <div class="flex items-center gap-3 sm:ml-auto">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div class="text-right sm:text-left">
                    <p class="font-black text-slate-800 text-base md:text-lg leading-none">{{ $activeYear->year }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">SEMESTER {{ strtoupper($activeYear->semester) }}</p>
                </div>
            </div>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-100 text-rose-700 text-xs font-bold">
                <i class="fa-solid fa-triangle-exclamation"></i> Belum Diatur
            </span>
        @endif
    </div>

    {{-- ─── STATUS NILAI RAPOR ─── --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Daftar Mengajar</p>
                <h3 class="font-extrabold text-slate-800 text-lg mt-0.5">Daftar Pengolahan Nilai Rapor</h3>
                <p class="text-xs text-slate-400 mt-0.5">Pilih salah satu kelas untuk mengelola nilai akhir dan deskripsi rapor.</p>
            </div>
        </div>

        @if($assignments->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center">
                <div class="w-14 h-14 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h4 class="font-extrabold text-slate-800">Belum Ada Penugasan</h4>
                <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Anda belum memiliki penugasan mengajar aktif. Hubungi administrator sekolah.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignmentStats as $stat)
                @php
                    $a = $stat['assignment'];
                    $pct = $stat['needed'] > 0 ? round(($stat['filled'] / $stat['needed']) * 100) : 0;
                    $status = $stat['status'];

                    // Configure badges and buttons based on status
                    if ($status === 'sudah_dikirim') {
                        $badgeText = "Sudah Dikirim";
                        $badgeStyle = "text-emerald-700 bg-emerald-50 border-emerald-100";
                        $buttonText = "Lihat Detail Rapor";
                        $buttonIcon = "fa-solid fa-eye";
                        $buttonColor = "bg-slate-700 hover:bg-slate-800 shadow-slate-700/10";
                    } elseif ($status === 'draft_deskripsi') {
                        $badgeText = "Draft Deskripsi";
                        $badgeStyle = "text-blue-700 bg-blue-50 border-blue-100";
                        $buttonText = "Lanjutkan Edit Rapor";
                        $buttonIcon = "fa-solid fa-pen-to-square";
                        $buttonColor = "bg-primary hover:bg-blue-800 shadow-primary/20";
                    } else {
                        $badgeText = "Belum Diproses";
                        $badgeStyle = "text-slate-500 bg-slate-100 border-slate-200";
                        $buttonText = "Mulai Proses Nilai";
                        $buttonIcon = "fa-solid fa-circle-play";
                        $buttonColor = "bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/20";
                    }
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between gap-5 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-book text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-slate-800 text-sm leading-tight truncate" title="{{ $a->subject->name }}">{{ $a->subject->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ $a->classroom->name }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase shrink-0 {{ $badgeStyle }}">
                                {{ $badgeText }}
                            </span>
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-2 gap-2 mt-5 py-3 px-4 bg-slate-50 rounded-xl text-center">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Siswa</p>
                                <p class="text-sm font-black text-slate-700 mt-0.5">{{ $stat['total'] }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">KKM Mapel</p>
                                <p class="text-sm font-black text-slate-700 mt-0.5">{{ $a->subject->kkm ?? 75 }}</p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-4">
                            <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                                <span>Kelengkapan Entri Nilai</span>
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $pct == 100 ? 'bg-emerald-500' : 'bg-amber-400' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Entry Button --}}
                    <div class="pt-2 border-t border-slate-50">
                        <a href="{{ route('teacher.laporan.show', $a->id) }}"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-white font-black text-xs uppercase tracking-wider transition shadow-md {{ $buttonColor }}">
                            <i class="{{ $buttonIcon }}"></i>
                            <span>{{ $buttonText }}</span>
                            <i class="fa-solid fa-chevron-right text-[9px] ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
