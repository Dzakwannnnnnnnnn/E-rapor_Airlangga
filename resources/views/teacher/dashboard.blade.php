@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard - e-Rapor')

@section('content')

<!-- Header Banner Section (Admin Style) -->
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <!-- Background shapes -->
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
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl md:text-4xl shadow-md border-4 border-white/20 transition-transform hover:rotate-12 duration-300">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Dashboard Guru</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-house-laptop text-base text-secondary opacity-95 animate-pulse"></i>
                            <span>Selamat Datang Kembali, <strong class="text-secondary">{{ Auth::user()->name }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NIP Badge -->
            <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3 relative z-10 hover:bg-white/15 transition-all">
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-id-card text-xs"></i>
                </div>
                <div class="text-left">
                    <p class="text-[9px] text-white/70 font-bold uppercase tracking-wider leading-none">NIP Guru</p>
                    <p class="text-xs font-black text-white mt-1">{{ $teacher->nip ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-7xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16">

    <!-- Active Academic Year Box (Overlapping) -->
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 absolute"></span>
                        <span class="pl-1">AKTIF</span>
                    </span>
                    <span class="text-xs text-slate-500">Anda dapat memasukkan nilai dan mengelola kelas untuk periode aktif ini.</span>
                </div>
            @else
                <h2 class="text-3xl font-black text-danger tracking-tight mt-2">Belum Diatur</h2>
                <p class="text-sm text-slate-500 mt-2">Silakan hubungi administrator untuk mengaktifkan tahun akademik.</p>
            @endif
        </div>

        @if($activeYear)
        <div class="hidden lg:flex items-center gap-8 bg-white py-4 px-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Sistem Rapor</p>
                        <p class="font-bold text-slate-700">Online 24/7</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Data Keamanan</p>
                        <p class="font-bold text-slate-700">Terbaca Terenkripsi</p>
                    </div>
                </div>
            </div>
            <div class="w-px h-16 bg-slate-100"></div>
            <div class="flex flex-col gap-2">
                <p class="text-[10px] text-slate-400 font-bold uppercase text-center">Semester</p>
                <span class="px-4 py-1.5 rounded-lg bg-primary text-white text-xs font-bold shadow-sm shadow-primary/30 uppercase">
                    {{ $activeYear->semester }}
                </span>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <!-- Kelas Diajar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-blue-50/30 group">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                        <i class="fa-solid fa-school"></i>
                    </div>
                    <span class="font-bold text-slate-700">Kelas Diajar</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-blue-600 leading-none">{{ $classroomCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Kelas</span>
                </div>
                <p class="text-xs text-slate-500 truncate font-semibold" title="{{ $classroomsList->pluck('name')->implode(', ') }}">
                    {{ $classroomsList->pluck('name')->implode(', ') ?: 'Tidak ada kelas' }}
                </p>
            </div>
            <a href="{{ route('teacher.kelas_saya.index') }}" class="mt-4 flex items-center justify-between text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors pt-3 border-t border-blue-100">
                <span>Detail Kelas</span> <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- Mata Pelajaran -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-emerald-50/30 group">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <span class="font-bold text-slate-700">Mata Pelajaran</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-emerald-600 leading-none">{{ $subjectCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Mapel</span>
                </div>
                <p class="text-xs text-slate-500 truncate font-semibold" title="{{ $assignments->pluck('subject.name')->unique()->implode(', ') }}">
                    {{ $assignments->pluck('subject.name')->unique()->implode(', ') ?: 'Tidak ada mapel' }}
                </p>
            </div>
            <a href="{{ route('teacher.kelas_saya.index') }}" class="mt-4 flex items-center justify-between text-xs font-bold text-emerald-600 hover:text-emerald-800 transition-colors pt-3 border-t border-emerald-100">
                <span>Detail Mapel</span> <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <!-- Siswa Terdaftar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between h-full bg-gradient-to-br from-white to-purple-50/30 group">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg transition-transform group-hover:scale-110">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span class="font-bold text-slate-700">Siswa Binaan</span>
                </div>
                <div class="flex items-end gap-2 mb-2">
                    <h3 class="text-4xl font-black text-purple-600 leading-none">{{ $studentCount }}</h3>
                    <span class="text-xs font-bold text-slate-400 mb-1">Siswa</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold">Siswa aktif di seluruh kelas Anda</p>
            </div>
            <a href="{{ route('teacher.kelas_saya.index') }}" class="mt-4 flex items-center justify-between text-xs font-bold text-purple-600 hover:text-purple-800 transition-colors pt-3 border-t border-purple-100">
                <span>Lihat Siswa</span> <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Main Content Split Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Classroom Assignments & Management Table -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-base">Kelola Penilaian Kelas</h3>
                        <p class="text-xs text-slate-400 mt-1">Daftar kelas dan mata pelajaran yang Anda ampu semester ini.</p>
                    </div>
                    <a href="{{ route('teacher.kelas_saya.index') }}" class="bg-primary hover:bg-primary-light text-white text-[10px] font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-chevron-right text-[8px]"></i> Detail Kelas
                    </a>
                </div>

                @if($assignments->isEmpty())
                    <div class="py-12 text-center">
                        <div class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                            <i class="fa-solid fa-folder-open text-2xl animate-pulse"></i>
                        </div>
                        <h4 class="font-extrabold text-slate-800 text-sm">Tidak Ada Data Pembagian Mengajar</h4>
                        <p class="text-xs text-slate-400 mt-1.5 max-w-sm mx-auto">Anda belum terdaftar mengajar di kelas manapun untuk semester ini. Hubungi administrator sekolah jika ini kesalahan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-slate-400 uppercase tracking-widest border-b border-slate-100 font-bold">
                                    <th class="py-3 px-2">Kelas</th>
                                    <th class="py-3 px-2">Mata Pelajaran</th>
                                    <th class="py-3 px-2">Kategori</th>
                                    <th class="py-3 px-2 text-center">Siswa</th>
                                    <th class="py-3 px-2 text-center">Asesmen</th>
                                    <th class="py-3 px-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    @php
                                        $isAcademic = $assignment->subject->type === 'academic';
                                        $badgeStyle = $isAcademic 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                                            : 'bg-indigo-50 text-indigo-700 border-indigo-100';
                                    @endphp
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors font-semibold text-slate-700">
                                        <td class="py-4 px-2 font-bold text-slate-800">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-primary flex items-center justify-center font-bold text-xs">
                                                    {{ substr($assignment->classroom->name, 0, 2) }}
                                                </div>
                                                <span>{{ $assignment->classroom->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-2 text-slate-800">{{ $assignment->subject->name }}</td>
                                        <td class="py-4 px-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full border text-[10px] font-bold uppercase tracking-wider {{ $badgeStyle }}">
                                                {{ $isAcademic ? 'Intrakurikuler' : 'Ekstrakurikuler' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-2 text-center font-bold text-slate-600">{{ $assignment->classroom->students->count() }}</td>
                                        <td class="py-4 px-2 text-center font-bold text-slate-600">{{ $assignment->assessments->count() }}</td>
                                        <td class="py-4 px-2 text-right">
                                            <a href="{{ route('teacher.grades.index', $assignment->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#f0f4ff] hover:bg-primary hover:text-white text-primary text-[10px] font-bold uppercase rounded-lg transition-all border border-[#dce5ff] hover:border-primary shadow-sm">
                                                <i class="fa-solid fa-edit text-[9px]"></i> Kelola Nilai
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Actions Sidebar & Teacher Profil -->
        <div class="lg:col-span-1 flex flex-col gap-6">
            
            <!-- Menu & Quick Actions -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col gap-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Menu & Pintasan Guru</h3>
                
                <a href="{{ route('teacher.kelas_saya.index') }}" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary hover:bg-[#f8faff] transition-all group font-bold text-slate-700 text-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-primary flex items-center justify-center transition-transform group-hover:scale-105">
                            <i class="fa-solid fa-book-open text-xs"></i>
                        </div>
                        <span>Kelas Saya</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-primary transition-colors"></i>
                </a>

                <a href="#" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary hover:bg-[#f8faff] transition-all group font-bold text-slate-700 text-sm opacity-60 hover:opacity-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center transition-transform group-hover:scale-105">
                            <i class="fa-solid fa-list-check text-xs"></i>
                        </div>
                        <div class="flex flex-col">
                            <span>Rekap Presensi</span>
                            <span class="text-[9px] text-purple-500 font-bold uppercase tracking-wider mt-0.5">Segera Hadir</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-lock text-xs text-slate-300 group-hover:text-primary transition-colors"></i>
                </a>

                <a href="#" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary hover:bg-[#f8faff] transition-all group font-bold text-slate-700 text-sm opacity-60 hover:opacity-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center transition-transform group-hover:scale-105">
                            <i class="fa-solid fa-print text-xs"></i>
                        </div>
                        <div class="flex flex-col">
                            <span>Cetak Lembar Nilai</span>
                            <span class="text-[9px] text-orange-500 font-bold uppercase tracking-wider mt-0.5">Segera Hadir</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-lock text-xs text-slate-300 group-hover:text-primary transition-colors"></i>
                </a>
            </div>

            <!-- Profile Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col gap-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Profil Pengajar</h3>
                
                <div class="space-y-3.5">
                    <div class="flex justify-between items-start gap-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nama Lengkap</span>
                        <span class="text-xs font-bold text-slate-700 text-right">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">NIP</span>
                        <span class="text-xs font-bold text-slate-700 text-right">{{ $teacher->nip ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jenis Kelamin</span>
                        <span class="text-xs font-bold text-slate-700 text-right">{{ $teacher->gender === 'L' ? 'Laki-laki' : ($teacher->gender === 'P' ? 'Perempuan' : 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Telepon</span>
                        <span class="text-xs font-bold text-slate-700 text-right">{{ $teacher->telp ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center gap-4">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Email Akun</span>
                        <span class="text-xs font-bold text-slate-700 text-right truncate max-w-[150px]" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
