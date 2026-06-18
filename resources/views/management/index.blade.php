@extends('layouts.dashboard')

@section('title', 'Pusat Manajemen - e-Rapor')

@section('content')

<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <!-- Background Decorations -->
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
                        <i class="fa-solid fa-grip"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Management</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-gears text-base text-secondary opacity-95"></i>
                            <span>Kelola seluruh data sistem e-Rapor</span>
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

    <!-- Overlapping Card: Pusat Manajemen -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-6 flex flex-col md:flex-row items-center justify-between gap-6 -mt-16 relative z-20">
        <div class="flex items-center gap-4 flex-1">
            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-md">
                <i class="fa-solid fa-shield-halved text-xl text-secondary"></i>
            </div>
            <div class="flex flex-col">
                <h4 class="font-extrabold text-slate-800 text-base leading-tight">Pusat Manajemen</h4>
                <p class="text-xs text-slate-500 mt-1">Kelola semua data akademik dan pengguna dalam satu tempat.</p>
            </div>
        </div>

        <div class="hidden md:block w-px h-12 bg-slate-100"></div>

        <div class="flex items-center gap-4 shrink-0 w-full md:w-auto justify-between md:justify-start pt-4 md:pt-0 border-t border-dashed border-slate-100 md:border-t-0">
            <div class="w-11 h-11 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0 border border-slate-100 group-hover:text-primary transition-colors">
                <i class="fa-solid fa-cubes text-sm text-primary/80"></i>
            </div>

            <div class="flex flex-col text-left">
                <span class="text-xs font-black text-slate-800 uppercase tracking-wide leading-none">
                    <span class="text-lg font-black text-primary mr-0.5">6</span> Fitur Kelola
                </span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 block">
                    Hak Akses Terintegrasi
                </span>
            </div>
        </div>
    </div>

    <!-- Section: Akun Pengguna -->
    <div class="space-y-4 pt-2">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 border border-purple-100">
                <i class="fa-solid fa-users text-sm"></i>
            </div>
            <div class="flex flex-col">
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Akun Pengguna</h4>
                <p class="text-[11px] text-slate-400">Kelola akun yang dapat mengakses sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Card: Guru -->
            <a href="{{ route('admin.teachers.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-primary">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-chalkboard-user text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Guru Pengajar</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola akun Guru Pengajar</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <!-- Card: Wali Murid -->
            <a href="{{ route('admin.parents.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-secondary">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-secondary/20 text-yellow-700 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-users-rays text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Wali Murid</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola akun Orang Tua / Wali Murid</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Section: Data Akademik -->
    <div class="space-y-4 pt-2">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <i class="fa-solid fa-graduation-cap text-sm"></i>
            </div>
            <div class="flex flex-col">
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wider">Data Akademik</h4>
                <p class="text-[11px] text-slate-400">Kelola data terkait proses pembelajaran</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Card: Siswa -->
            <a href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-emerald-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-user-graduate text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Siswa</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola data peserta didik</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <!-- Card: Kelas -->
            <a href="{{ Route::has('admin.classrooms.index') ? route('admin.classrooms.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-blue-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-school text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Kelas</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola kelas dan pembagian siswa</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <!-- Card: Mata Pelajaran -->
            <a href="{{ Route::has('admin.subjects.index') ? route('admin.subjects.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-amber-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Mata Pelajaran</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola daftar mata pelajaran</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>

            <!-- Card: Tahun Akademik -->
            <a href="{{ Route::has('admin.academic_years.index') ? route('admin.academic_years.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-violet-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-calendar-days text-lg"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Tahun Akademik</span>
                        <span class="text-xs text-slate-500 mt-0.5">Kelola periode tahun akademik</span>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                    <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
                </div>
            </a>
        </div>
    </div>

</div>

@endsection
