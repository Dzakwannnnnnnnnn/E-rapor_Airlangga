@extends('layouts.dashboard')

@section('title', 'Catatan & Kehadiran – ' . $student->name . ' · e-Rapor')

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
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Input Absensi & Catatan</h3>
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

    {{-- Input Form --}}
    <form action="{{ route('teacher.homeroom.student.store', $student->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- 1. ATTENDANCE INPUT CARD --}}
            <div class="md:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4">
                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-primary"></i> Data Kehadiran
                </h4>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="hadir" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider pl-0.5">Hadir</label>
                        <input type="number" name="hadir" id="hadir" value="{{ old('hadir', $attendance ? $attendance->hadir : 0) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-semibold text-slate-800 focus:outline-none transition-all shadow-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label for="sakit" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider pl-0.5">Sakit</label>
                        <input type="number" name="sakit" id="sakit" value="{{ old('sakit', $attendance ? $attendance->sakit : 0) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-semibold text-slate-800 focus:outline-none transition-all shadow-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label for="izin" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider pl-0.5">Izin</label>
                        <input type="number" name="izin" id="izin" value="{{ old('izin', $attendance ? $attendance->izin : 0) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-semibold text-slate-800 focus:outline-none transition-all shadow-sm">
                    </div>

                    <div class="space-y-1.5">
                        <label for="alpha" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider pl-0.5">Alpha</label>
                        <input type="number" name="alpha" id="alpha" value="{{ old('alpha', $attendance ? $attendance->alpha : 0) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-semibold text-slate-800 focus:outline-none transition-all shadow-sm">
                    </div>
                </div>
            </div>

            {{-- 2. HOMEROOM NOTES INPUT CARD --}}
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4 flex flex-col">
                <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-message text-primary"></i> Catatan Wali Kelas
                </h4>

                <div class="space-y-1.5 flex-1 flex flex-col">
                    <label for="description" class="block text-[10px] font-black text-slate-500 uppercase tracking-wider pl-0.5">Catatan Perkembangan Belajar</label>
                    <textarea name="description" id="description" rows="10"
                              class="w-full p-4 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-semibold text-slate-800 focus:outline-none transition-all shadow-sm flex-1 leading-relaxed"
                              placeholder="Tuliskan catatan wali kelas untuk siswa ini di rapor. Catatan ini berisi saran perkembangan akademis, sikap, maupun kepribadian siswa selama semester ini...">{{ old('description', $reportCard ? $reportCard->description : '') }}</textarea>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 bg-slate-50 border border-slate-100 p-4 rounded-2xl">
            <a href="{{ route('teacher.homeroom.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-100 transition-all">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Catatan &amp; Kehadiran
            </button>
        </div>

    </form>

</div>

@endsection
