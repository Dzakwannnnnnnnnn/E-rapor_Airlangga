@extends('layouts.dashboard')

@section('title', 'Manajemen Kelas - e-Rapor')

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
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-chalkboard"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Manajemen Kelas</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                            <span>Kelola Data Ruang Kelas & Kompetensi Keahlian</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shrink-0 mt-2 md:mt-4">
                <a href="{{ route('admin.classrooms.create') }}" class="inline-flex items-center gap-2 bg-secondary text-primary hover:bg-white font-black text-sm px-6 py-3 rounded-full transition-all shadow-lg active:scale-95 cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
                </a>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6">

    <div class="max-w-xl mx-auto -mt-16 mb-6 relative z-20 px-4">
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-secondary/20 text-yellow-700 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-layer-group text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Rombongan Belajar</span>
                <span class="text-xl font-black text-slate-800 leading-none mt-1 block">
                    {{ $classrooms->total() }} <span class="text-sm font-bold text-slate-500">Kelas</span>
                </span>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-0 pb-12">

        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form action="{{ request()->url() }}" method="GET" class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas atau kompetensi keahlian..." class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">

                @if(request('search'))
                    <a href="{{ request()->url() }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-border-all text-yellow-600"></i>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Daftar Rombongan Belajar</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($classrooms as $classroom)
                    <div class="relative bg-white rounded-xl border border-slate-100 p-4 sm:p-5 flex flex-col justify-between gap-4 hover:shadow-md hover:border-slate-200 transition-all duration-300 group">

                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-blue-50 text-primary flex items-center justify-center font-black text-xs sm:text-sm shrink-0 border border-blue-100/50 tracking-wider uppercase">
                                {{ strtoupper(substr($classroom->name, 0, 3)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col items-start gap-1">
                                    <span class="font-bold text-slate-800 text-sm sm:text-base block truncate w-[85%] group-hover:text-primary transition-colors uppercase">
                                        {{ $classroom->name }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-500 block truncate mt-1">
                                    <i class="fa-solid fa-graduation-cap mr-1.5 opacity-60"></i>{{ $classroom->major }}
                                </span>
                                <span class="text-xs text-slate-500 block truncate mt-1">
                                    <i class="fa-solid fa-chalkboard-user mr-1.5 opacity-60"></i>Wali: <strong class="text-slate-700">{{ $classroom->homeroomTeacher ? $classroom->homeroomTeacher->user->name : 'Belum Ditentukan' }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 pt-0.5">
                            <span class="inline-flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg text-xs font-medium border border-slate-100 text-slate-600">
                                <i class="fa-solid fa-users text-slate-400 text-[10px]"></i>
                                Kapasitas:
                                <span class="font-bold text-slate-700 pl-0.5 font-mono">
                                    {{ $classroom->students_count ?? 0 }}
                                </span> Siswa
                            </span>

                            <span class="inline-flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg text-xs font-medium border border-slate-100 text-slate-600">
                                <i class="fa-solid fa-calendar text-slate-400 text-[10px]"></i>
                                TA: <span class="font-bold text-slate-700 pl-0.5">2025/2026</span>
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-2 pt-3 border-t border-dashed border-slate-100 mt-auto w-full">
                            <a href="{{ route('admin.classrooms.show', $classroom->id) }}" class="inline-flex justify-center items-center gap-1.5 px-2 py-2 bg-slate-50 text-slate-600 hover:text-primary hover:bg-blue-50/50 rounded-lg transition-colors text-xs font-bold border border-slate-100" title="Lihat Anggota Kelas">
                                <i class="fa-solid fa-eye text-xs sm:text-sm opacity-80"></i> <span class="truncate">Detail</span>
                            </a>

                            <a href="{{ route('admin.classrooms.edit', $classroom->id) }}" class="inline-flex justify-center items-center gap-1.5 px-2 py-2 bg-slate-50 text-slate-600 hover:text-amber-600 hover:bg-amber-50/50 rounded-lg transition-colors text-xs font-bold border border-slate-100" title="Ubah Konfigurasi">
                                <i class="fa-solid fa-pen-to-square text-xs sm:text-sm opacity-80"></i> <span class="truncate">Edit</span>
                            </a>

                            <form
                                id="delete-classroom-{{ $classroom->id }}"
                                action="{{ route('admin.classrooms.destroy', $classroom->id) }}"
                                method="POST"
                                class="w-full inline-block"
                                data-confirm="Data Rombel &quot;{{ $classroom->name }}&quot; akan dihapus secara permanen dari sistem integrasi e-Rapor."
                                data-confirm-title="Hapus Entri Kelas?"
                                data-confirm-btn="Ya, Hapus Sekarang">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-1.5 px-2 py-2 bg-red-50/50 text-red-500 hover:bg-red-100 rounded-lg transition-colors text-xs font-bold border border-red-100/50 cursor-pointer">
                                    <i class="fa-solid fa-trash-can text-xs sm:text-sm opacity-80"></i> <span class="truncate">Hapus</span>
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-12 text-center flex flex-col items-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                            <i class="fa-solid fa-folder-open text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-600">Belum ada data Rombongan Belajar</p>
                        @if(request('search'))
                            <p class="text-xs text-slate-400 mt-1">Pencarian untuk parameter "{{ request('search') }}" tidak menghasilkan kecocokan.</p>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($classrooms->hasPages())
                <div class="mt-6">
                    {{ $classrooms->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
