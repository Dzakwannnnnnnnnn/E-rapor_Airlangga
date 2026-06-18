@extends('layouts.dashboard')

@section('title', 'Edit Mata Pelajaran - ' . $subject->name . ' - e-Rapor')

@section('back_url', route('admin.subjects.index'))

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

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20 uppercase select-none">
                    {{ strtoupper(substr($subject->name, 0, 2)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-md border border-white">
                    <i class="fa-solid fa-book-bookmark text-[10px]"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-fingerprint text-[9px]"></i> ID Mapel: #{{ $subject->id }}
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Edit Data Mata Pelajaran</h3>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-base text-secondary opacity-95"></i>
                        <span>Panel Manajemen Kurikulum</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-base text-secondary opacity-95"></i>
                        <span>Tipe: <strong class="text-white">{{ $subject->type === 'academic' ? 'Akademik' : 'Ekstrakurikuler' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 space-y-6">

    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0">
        <a href="{{ route('admin.subjects.index') }}"
           class="inline-flex items-center gap-4 bg-white rounded-2xl shadow-md border border-slate-100 p-4 hover:bg-slate-50 transition-colors group">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="pr-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none mt-1 block">Kembali ke Daftar Mata Pelajaran</span>
            </div>
        </a>
    </div>

    <form id="edit-subject-form" method="POST" action="{{ route('admin.subjects.update', $subject->id) }}" class="space-y-6 px-4 sm:px-0">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Perbarui Informasi Mata Pelajaran</h4>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Nama Mata Pelajaran</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-book text-sm"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('name') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm uppercase"
                                placeholder="Masukkan nama mata pelajaran">
                        </div>
                        @error('name')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="type" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Tipe Pembelajaran</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-layer-group text-sm"></i>
                            </div>
                            <select name="type" id="type" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('type') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled>Pilih Tipe Pembelajaran</option>
                                <option value="academic" {{ old('type', $subject->type) == 'academic' ? 'selected' : '' }}>Akademik</option>
                                <option value="extracurricular" {{ old('type', $subject->type) == 'extracurricular' ? 'selected' : '' }}>Ekstrakurikuler</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('type')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-primary text-slate-900 rounded-2xl p-4 shadow-sm flex items-center gap-4 select-none">
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-info text-lg"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-extrabold text-xs uppercase tracking-wide text-primary">Dampak Perubahan Struktur</h5>
                <p class="text-xs text-slate-600 mt-0.5 leading-normal">Mengubah tipe pembelajaran atau komponen nama mata pelajaran secara langsung akan <span class="font-bold text-slate-800">mengubah format transkrip nilai akademik</span> siswa yang menggunakan kurikulum bersangkutan.</p>
            </div>
        </div>

        <div class="hidden md:flex items-center justify-end gap-4 pt-2 pb-6">
            <a href="{{ route('admin.subjects.index') }}"
               class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-50 transition-all text-center">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                Perbarui Mata Pelajaran
            </button>
        </div>

    </form>
</div>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 pb-safe">
    <div class="flex items-center gap-3 max-w-lg mx-auto">
        <a href="{{ route('admin.subjects.index') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
            <i class="fa-solid fa-xmark text-slate-400"></i>
            Batal
        </a>
        <button type="submit" form="edit-subject-form"
                class="flex-[2] flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-br from-primary to-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all border-b-2 border-blue-900">
            <i class="fa-solid fa-floppy-disk"></i>
            Perbarui Data
        </button>
    </div>
</div>
@endsection
