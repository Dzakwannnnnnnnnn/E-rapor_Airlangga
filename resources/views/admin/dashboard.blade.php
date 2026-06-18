@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - e-Rapor')

@section('content')

<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
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
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Dashboard Admin</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-house-laptop text-base text-secondary opacity-95"></i>
                            <span>Ikhtisar Data Sistem e-Rapor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-7xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16">

    <div class="bg-[#f8faff] rounded-2xl shadow-sm border border-blue-100 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-8 -mt-20 relative z-20">
        <div class="flex-1">
            <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-widest mb-3">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Tahun Akademik Aktif</span>
            </div>
            @if($activeYear)
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">{{ $activeYear->year }}</h2>
                <h3 class="text-xl md:text-2xl font-bold text-slate-600 mt-1">Semester {{ ucfirst($activeYear->semester) }}</h3>

                <div class="flex items-center gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> AKTIF
                    </span>
                    <span class="text-xs text-slate-500">Periode ini sedang berjalan dan digunakan dalam proses penilaian.</span>
                </div>
            @else
                <h2 class="text-3xl font-black text-danger tracking-tight mt-2">Belum Diatur</h2>
                <p class="text-sm text-slate-500 mt-2">Silakan aktifkan tahun ajaran di menu pengaturan.</p>
            @endif
        </div>

        @if($activeYear)
        <div class="hidden lg:flex items-center gap-8 bg-white py-4 px-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Dimulai</p>
                        <p class="font-bold text-slate-700">Pertengahan Tahun</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Berakhir</p>
                        <p class="font-bold text-slate-700">Akhir Tahun</p>
                    </div>
                </div>
            </div>
            <div class="w-px h-16 bg-slate-100"></div>
            <div class="flex flex-col gap-2">
                <p class="text-[10px] text-slate-400 font-bold uppercase">Semester</p>
                <span class="px-4 py-1.5 rounded-lg bg-primary text-white text-xs font-bold shadow-sm shadow-primary/30">
                    {{ ucfirst($activeYear->semester) }}
                </span>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-blue-50/30">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <span class="font-bold text-slate-700">Siswa</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-blue-600 leading-none">{{ $studentCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Data</span>
                </div>
                <p class="text-xs text-slate-500">Peserta didik terdaftar</p>
            </div>
            <a href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}" class="mt-4 flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors pt-3 border-t border-blue-100">
                <span>Lihat Detail</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-emerald-50/30">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <span class="font-bold text-slate-700">Guru</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-emerald-600 leading-none">{{ $teacherCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Data</span>
                </div>
                <p class="text-xs text-slate-500">Guru pengajar terdaftar</p>
            </div>
            <a href="{{ Route::has('admin.teachers.index') ? route('admin.teachers.index') : '#' }}" class="mt-4 flex items-center justify-between text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors pt-3 border-t border-emerald-100">
                <span>Lihat Detail</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-purple-50/30">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <span class="font-bold text-slate-700">Kelas</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-purple-600 leading-none">{{ $classroomCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Data</span>
                </div>
                <p class="text-xs text-slate-500">Kelas aktif tersedia</p>
            </div>
            <a href="{{ Route::has('admin.classrooms.index') ? route('admin.classrooms.index') : '#' }}" class="mt-4 flex items-center justify-between text-xs font-bold text-purple-600 hover:text-purple-800 transition-colors pt-3 border-t border-purple-100">
                <span>Lihat Detail</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-orange-50/30">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span class="font-bold text-slate-700">Mata Pelajaran</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-orange-600 leading-none">{{ $subjectCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Data</span>
                </div>
                <p class="text-xs text-slate-500">Akademik & Ekstrakurikuler</p>
            </div>
            <a href="{{ Route::has('admin.subjects.index') ? route('admin.subjects.index') : '#' }}" class="mt-4 flex items-center justify-between text-xs font-bold text-orange-600 hover:text-orange-800 transition-colors pt-3 border-t border-orange-100">
                <span>Lihat Detail</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="space-y-4 pt-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-lg">Akun Pengguna</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola akun yang dapat mengakses sistem e-Rapor.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between h-full">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl shrink-0 shadow-sm">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">Guru Pengajar</h4>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-2xl font-black text-blue-600">{{ $teacherCount }}</span>
                            <span class="text-xs font-bold text-slate-400">Akun</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Kelola akun guru pengajar</p>
                    </div>
                </div>
                <a href="{{ Route::has('admin.teachers.index') ? route('admin.teachers.index') : '#' }}" class="mt-6 flex items-center justify-between w-full py-2.5 px-4 rounded-xl text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors text-center border border-blue-100/50">
                    <span class="mx-auto">Kelola Guru</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between h-full">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500 text-white flex items-center justify-center text-2xl shrink-0 shadow-sm">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">Wali Murid</h4>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-2xl font-black text-emerald-600">{{ $parentCount }}</span>
                            <span class="text-xs font-bold text-slate-400">Akun</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Kelola akun orang tua / wali</p>
                    </div>
                </div>
                <a href="{{ Route::has('admin.parents.index') ? route('admin.parents.index') : '#' }}" class="mt-6 flex items-center justify-between w-full py-2.5 px-4 rounded-xl text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-colors text-center border border-emerald-100/50">
                    <span class="mx-auto">Kelola Wali Murid</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-4 pt-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-lg">Data Akademik</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola seluruh data akademik sekolah.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Siswa</h4>
                </div>
                <p class="text-xs text-slate-500 mb-6 flex-1">Kelola data siswa dan informasi pribadi</p>
                <a href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}" class="flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors pt-3 border-t border-slate-100">
                    <span>Kelola Siswa</span> <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <i class="fa-solid fa-school-flag"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Kelas</h4>
                </div>
                <p class="text-xs text-slate-500 mb-6 flex-1">Kelola data kelas dan jurusan</p>
                <a href="{{ Route::has('admin.classrooms.index') ? route('admin.classrooms.index') : '#' }}" class="flex items-center justify-between text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors pt-3 border-t border-slate-100">
                    <span>Kelola Kelas</span> <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Mata Pelajaran</h4>
                </div>
                <p class="text-xs text-slate-500 mb-6 flex-1">Kelola mata pelajaran akademik & ekstrakurikuler</p>
                <a href="{{ Route::has('admin.subjects.index') ? route('admin.subjects.index') : '#' }}" class="flex items-center justify-between text-xs font-bold text-purple-600 hover:text-purple-800 transition-colors pt-3 border-t border-slate-100">
                    <span>Kelola Mapel</span> <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Tahun Akademik</h4>
                </div>
                <p class="text-xs text-slate-500 mb-6 flex-1">Kelola periode tahun akademik aktif</p>
                <a href="{{ Route::has('admin.academic_years.index') ? route('admin.academic_years.index') : '#' }}" class="flex items-center justify-between text-xs font-bold text-orange-600 hover:text-orange-800 transition-colors pt-3 border-t border-slate-100">
                    <span>Kelola Tahun Akademik</span> <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
