@extends('layouts.dashboard')
@section('title', 'Pusat Pengesahan Rapor · e-Rapor')
@section('content')

{{-- Hero --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-20 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                <i class="fa-solid fa-stamp"></i>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2 uppercase tracking-wider">
                    <i class="fa-solid fa-calendar text-[9px]"></i>
                    {{ $activeYear ? ucfirst($activeYear->semester) . ' ' . $activeYear->year : '—' }}
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Pusat Pengesahan Rapor</h3>
                <p class="text-white/70 text-xs md:text-sm mt-1.5">Pilih kelas untuk mengesahkan rapor siswa satu per satu atau sekaligus</p>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto -mt-14 space-y-6 px-4 md:px-0 pb-16 relative z-20">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-msg" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
            <button onclick="document.getElementById('flash-msg').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-err" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-2xl px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
            {{ session('error') }}
            <button onclick="document.getElementById('flash-err').remove()" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    {{-- Stat Summary --}}
    @php
        $totalAll     = $classroomsStats->sum('total');
        $submittedAll = $classroomsStats->sum('submitted');
        $validatedAll = $classroomsStats->sum('validated');
        $pendingAll   = $classroomsStats->sum(fn($s) => ($s['submitted'] - $s['validated']));
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm text-center">
            <p class="text-2xl font-black text-primary">{{ $totalAll }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-1">Total Siswa</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-blue-100 shadow-sm text-center">
            <p class="text-2xl font-black text-blue-600">{{ $submittedAll }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-1">Sudah Diajukan</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border {{ $pendingAll > 0 ? 'border-amber-200' : 'border-slate-100' }} shadow-sm text-center">
            <p class="text-2xl font-black {{ $pendingAll > 0 ? 'text-amber-500' : 'text-slate-400' }}">{{ $pendingAll }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-1">Menunggu Sah</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-sm text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $validatedAll }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-1">Sudah Disahkan</p>
        </div>
    </div>

    {{-- Classroom Cards Grid --}}
    <div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-layer-group text-sm"></i>
            </div>
            <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Pilih Kelas</h4>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($classroomsStats as $stat)
            @php
                $pending   = $stat['submitted'] - $stat['validated'];
                $pct       = $stat['total'] > 0 ? round(($stat['validated'] / $stat['total']) * 100) : 0;
                $allDone   = $pct === 100 && $stat['total'] > 0;
                $hasPending = $pending > 0;
            @endphp
            <a href="{{ route('headmaster.pengesahan.kelas', $stat['classroom']->id) }}"
               class="bg-white rounded-2xl border {{ $hasPending ? 'border-amber-200 hover:border-amber-400' : ($allDone ? 'border-emerald-200' : 'border-slate-100') }} shadow-sm p-5 flex flex-col gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">

                <div class="flex items-start justify-between">
                    <div>
                        <h5 class="font-black text-slate-800 text-base group-hover:text-primary transition-colors">{{ $stat['classroom']->name }}</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-0.5">
                            Wali: {{ $stat['classroom']->homeroomTeacher?->user?->name ?? 'Belum Ditentukan' }}
                        </p>
                    </div>
                    @if($hasPending)
                        <span class="shrink-0 inline-flex items-center gap-1 text-[9px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full uppercase">
                            <i class="fa-solid fa-hourglass-half text-[8px]"></i> {{ $pending }} Pending
                        </span>
                    @elseif($allDone)
                        <span class="shrink-0 inline-flex items-center gap-1 text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full uppercase">
                            <i class="fa-solid fa-check text-[8px]"></i> Selesai
                        </span>
                    @endif
                </div>

                <div>
                    <div class="flex justify-between text-[10px] font-bold text-slate-500 mb-1.5">
                        <span>{{ $stat['validated'] }}/{{ $stat['total'] }} disahkan</span>
                        <span class="font-black {{ $allDone ? 'text-emerald-600' : ($hasPending ? 'text-amber-600' : 'text-primary') }}">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="{{ $allDone ? 'bg-emerald-500' : ($hasPending ? 'bg-amber-400' : 'bg-primary') }} h-full rounded-full transition-all duration-500"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex gap-3 text-[10px] font-bold text-slate-500">
                        <span><i class="fa-solid fa-users text-slate-300 mr-1"></i>{{ $stat['total'] }} siswa</span>
                        <span><i class="fa-solid fa-paper-plane text-blue-300 mr-1"></i>{{ $stat['submitted'] }} diajukan</span>
                    </div>
                    <span class="text-[10px] font-black text-primary group-hover:translate-x-0.5 transition-transform">
                        Buka <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
