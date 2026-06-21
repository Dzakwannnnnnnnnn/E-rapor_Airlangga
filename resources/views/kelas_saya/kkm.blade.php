@extends('layouts.dashboard')

@section('title', 'KKM Mata Pelajaran - e-Rapor')

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

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">KKM Mata Pelajaran</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-user text-base text-secondary opacity-95"></i>
                            <span>Atur Kriteria Ketuntasan Minimal mata pelajaran yang diampu</span>
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

    {{-- Back navigation --}}
    <div class="-mt-16 relative z-20">
        <a href="{{ route('teacher.kelas_saya.index') }}"
           class="flex sm:inline-flex items-center gap-3 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase block">Kembali ke Kelas Saya</span>
            </div>
        </a>
    </div>

    {{-- KKM Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
        <div>
            <h4 class="font-extrabold text-slate-800 text-base leading-tight">Pengaturan Batas Nilai KKM</h4>
            <p class="text-xs text-slate-500 mt-1">
                Atur batas KKM per mata pelajaran di bawah ini. KKM ini berlaku untuk seluruh kelas yang diampu pada mata pelajaran bersangkutan.
            </p>
        </div>

        @if($subjects->isEmpty())
            <div class="bg-slate-50 border border-slate-150 rounded-2xl p-8 text-center">
                <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-folder-open text-xl"></i>
                </div>
                <h5 class="font-bold text-slate-800 text-sm">Tidak Ada Mata Pelajaran</h5>
                <p class="text-xs text-slate-400 mt-1">Anda tidak mengampu mata pelajaran apa pun pada periode aktif.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subj)
                    <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5 flex flex-col justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <div class="min-w-0">
                                <h5 class="font-bold text-slate-800 text-sm truncate" title="{{ $subj->name }}">{{ $subj->name }}</h5>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                    {{ $subj->type === 'academic' ? 'Intrakurikuler' : 'Ekstrakurikuler' }}
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('teacher.kelas_saya.set-kkm', $subj->id) }}" method="POST" class="flex items-center gap-2 pt-2 border-t border-slate-100/50">
                            @csrf
                            <div class="relative flex-1">
                                <input type="number" name="kkm" value="{{ $subj->kkm ?? 75 }}" min="0" max="100" required
                                       class="w-full pl-3 pr-8 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-primary text-xs font-bold text-slate-800">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 font-bold">PTS</span>
                            </div>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-primary hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition shadow-sm shrink-0">
                                Simpan
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
