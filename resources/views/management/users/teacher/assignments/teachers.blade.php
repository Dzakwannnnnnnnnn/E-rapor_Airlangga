@extends('layouts.dashboard')

@section('title', 'Penugasan Mengajar - e-Rapor')

@section('back_url', route('admin.management.index'))

@section('content')

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
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Penugasan Mengajar</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                            <span>Pilih guru untuk mengelola penugasan kelas & mata pelajaran</span>
                        </div>
                        @if($activeYear)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-calendar-days text-base text-secondary opacity-95"></i>
                            <span>TA Aktif: {{ $activeYear->year }} — {{ $activeYear->semester == 1 ? 'Ganjil' : 'Genap' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6">

    {{-- Stats + Back --}}
    <div class="-mt-16 mb-4 relative z-20 px-4 sm:px-0 flex flex-col sm:flex-row gap-3">
        {{-- Back --}}
        <a href="{{ route('admin.management.index') }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Manajemen</span>
            </div>
        </a>

        {{-- Total guru --}}
        <div class="flex-1 bg-white rounded-2xl shadow-md border border-slate-100 p-3 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chalkboard-user text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Guru</span>
                <span class="text-xl font-black text-slate-800 leading-none mt-0.5 block">{{ $teachers->total() }} <span class="text-sm font-bold text-slate-500">Pendidik</span></span>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-0 pb-16">

        {{-- Search --}}
        <div class="mb-6">
            <form action="{{ request()->url() }}" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama atau email guru..."
                    class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                @if($search)
                    <a href="{{ request()->url() }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors" title="Reset">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Section title --}}
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-chalkboard-user text-primary"></i>
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Pilih Guru untuk Kelola Penugasan</h4>
        </div>

        {{-- Teacher Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($teachers as $user)
                @php
                    $teacher = $user->teacher;
                    $gender  = $teacher->gender ?? '';

                    if ($gender === 'L') {
                        $avatarBg   = 'bg-blue-50 text-blue-600 border-blue-100';
                        $genderIcon = 'fa-mars text-blue-500';
                        $genderLbl  = 'Laki-laki';
                        $genderBg   = 'bg-blue-50 text-blue-600 border-blue-100';
                    } elseif ($gender === 'P') {
                        $avatarBg   = 'bg-pink-50 text-pink-600 border-pink-100';
                        $genderIcon = 'fa-venus text-pink-500';
                        $genderLbl  = 'Perempuan';
                        $genderBg   = 'bg-pink-50 text-pink-600 border-pink-100';
                    } else {
                        $avatarBg   = 'bg-primary/10 text-primary border-primary/10';
                        $genderIcon = 'fa-venus-mars text-slate-400';
                        $genderLbl  = '-';
                        $genderBg   = 'bg-slate-50 text-slate-600 border-slate-100';
                    }

                    $isVerified     = !is_null($user->email_verified_at);
                    $assignmentCount = $teacher ? $teacher->assignments()->count() : 0;
                @endphp

                <div class="relative bg-white rounded-2xl border border-slate-100 p-5 flex flex-col gap-4 hover:shadow-md hover:border-slate-200 transition-all duration-300 group">

                    {{-- Profil --}}
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl {{ $avatarBg }} flex items-center justify-center font-black text-base shrink-0 border tracking-wide">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-slate-800 text-sm block truncate group-hover:text-primary transition-colors">
                                {{ $user->name }}
                            </span>
                            <span class="text-xs text-slate-500 block truncate mt-0.5">
                                <i class="fa-solid fa-envelope mr-1 opacity-60"></i>{{ $user->email }}
                            </span>
                            <div class="flex items-center gap-1.5 mt-1 text-xs">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">NIP:</span>
                                <span class="font-semibold text-slate-700 bg-slate-50 px-2 py-0.5 rounded border border-slate-100 text-[11px]">
                                    {{ $teacher->nip ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Meta badges --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $genderBg }}">
                            <i class="fa-solid {{ $genderIcon }} text-[11px]"></i>
                            {{ $genderLbl }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border
                            {{ $assignmentCount > 0 ? 'bg-cyan-50 text-cyan-700 border-cyan-100' : 'bg-slate-50 text-slate-500 border-slate-100' }}">
                            <i class="fa-solid fa-list-check text-[11px]"></i>
                            {{ $assignmentCount }} Penugasan
                        </span>
                    </div>

                    {{-- Action --}}
                    @if($teacher)
                        <a href="{{ route('admin.teachers.assignments.index', $teacher->id) }}"
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl transition-all font-black text-xs uppercase tracking-wider border border-primary/20 hover:border-primary group/btn">
                            <i class="fa-solid fa-chalkboard text-sm"></i>
                            Kelola Penugasan
                            <i class="fa-solid fa-chevron-right text-[10px] transition-transform group-hover/btn:translate-x-1"></i>
                        </a>
                    @else
                        <div class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 text-slate-400 rounded-xl font-black text-xs uppercase tracking-wider border border-slate-100 cursor-not-allowed">
                            <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            Profil Guru Belum Lengkap
                        </div>
                    @endif
                </div>

            @empty
                <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-slate-100 p-12 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                        <i class="fa-solid fa-users-slash text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600">Belum ada data Guru</p>
                    @if($search)
                        <p class="text-xs text-slate-400 mt-1">Pencarian untuk "{{ $search }}" tidak ditemukan.</p>
                    @endif
                    <a href="{{ route('admin.teachers.create') }}"
                       class="mt-4 inline-flex items-center gap-2 bg-primary text-white text-xs font-black uppercase tracking-wider px-5 py-2.5 rounded-xl shadow-md shadow-primary/20 hover:bg-opacity-90 transition-all">
                        <i class="fa-solid fa-plus"></i> Tambah Guru Baru
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $teachers->links() }}
        </div>

    </div>
</div>

@endsection
