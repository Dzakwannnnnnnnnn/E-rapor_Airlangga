@extends('layouts.dashboard')

@section('title', 'Pusat Pengesahan Rapor - e-Rapor')

@section('back_url', route('admin.management.index'))

@section('content')
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Pengesahan Rapor</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                            <span>Verifikasi & Sahkan Rapor Hasil Belajar Siswa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16">

    {{-- Stats Summary --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 flex flex-col sm:flex-row items-center justify-between gap-5 -mt-16 relative z-20">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-md">
                <i class="fa-solid fa-calendar-check text-xl text-secondary"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Tahun Ajaran Aktif</p>
                <p class="font-black text-slate-800 text-base md:text-lg leading-none mt-1">{{ $activeYear->year }} (Semester {{ ucfirst($activeYear->semester) }})</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-semibold shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Classrooms Grid --}}
    <div class="space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-border-all text-primary"></i>
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Pengesahan Rapor Per Kelas</h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classroomsStats as $stat)
                @php
                    $c = $stat['classroom'];
                    $pct = $stat['total'] > 0 ? round(($stat['validated'] / $stat['total']) * 100) : 0;
                    $pendingCount = $stat['submitted'] - $stat['validated'];
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between gap-5 hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-graduation-cap text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-slate-800 text-sm leading-tight truncate uppercase">{{ $c->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Wali: {{ $c->homeroomTeacher ? $c->homeroomTeacher->user->name : 'Belum Ada' }}</p>
                                </div>
                            </div>
                            @if($pendingCount > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-[9px] font-black uppercase shrink-0">
                                    {{ $pendingCount }} Butuh Sah
                                </span>
                            @endif
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-2 mt-5 py-3 px-3 bg-slate-50 rounded-xl text-center">
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Siswa</p>
                                <p class="text-xs font-black text-slate-700 mt-0.5">{{ $stat['total'] }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Diajukan</p>
                                <p class="text-xs font-black text-slate-700 mt-0.5">{{ $stat['submitted'] }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Disahkan</p>
                                <p class="text-xs font-black text-slate-700 mt-0.5 text-emerald-600">{{ $stat['validated'] }}</p>
                            </div>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-4">
                            <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1">
                                <span>Kemajuan Pengesahan</span>
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $pct == 100 ? 'bg-emerald-500' : 'bg-primary' }}"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="pt-2 border-t border-slate-50">
                        <a href="{{ route('admin.report-cards.classroom', $c->id) }}"
                           class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-white bg-primary hover:bg-blue-800 font-black text-xs uppercase tracking-wider transition shadow-md">
                            <i class="fa-solid fa-stamp"></i>
                            <span>Kelola Pengesahan</span>
                            <i class="fa-solid fa-chevron-right text-[9px] ml-1"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center">
                    <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-folder-open text-2xl"></i>
                    </div>
                    <h4 class="font-extrabold text-slate-800">Tidak Ada Rombongan Belajar</h4>
                    <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Silakan buat rombongan belajar terlebih dahulu di panel manajemen kelas.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
