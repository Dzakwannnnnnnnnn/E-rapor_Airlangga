@extends('layouts.dashboard')

@section('title', 'Manajemen Guru - e-Rapor')

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
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Manajemen Guru</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                            <span>Kelola Data Guru Pengajar</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shrink-0 mt-2 md:mt-4">
                <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 bg-secondary text-primary hover:bg-white font-black text-sm px-6 py-3 rounded-full transition-all shadow-lg active:scale-95">
                    <i class="fa-solid fa-plus"></i> Tambah Guru
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
            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chalkboard-user text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Guru Pengajar</span>
                <span class="text-xl font-black text-slate-800 leading-none mt-1 block">{{ $teachers->total() }} <span class="text-sm font-bold text-slate-500">Akun</span></span>
            </div>
        </div>
    </div>

    <div class="px-4 md:px-0 pb-12">

        <div class="mb-6 flex flex-col sm:flex-row gap-3">
            <form action="{{ request()->url() }}" method="GET" class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email guru..." class="w-full pl-11 pr-12 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">

                @if($search)
                    <a href="{{ request()->url() }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2 select-none">
                <i class="fa-solid fa-address-book text-primary text-xs"></i>
                <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Buku Induk & Data Pengajar</h4>
            </div>

            <div class="grid grid-cols-1 gap-4 md:hidden">
                @forelse ($teachers as $teacher)
                    @php
                        $gender = $teacher->teacher->gender ?? '';

                        if ($gender === 'L') {
                            $genderClass = 'bg-blue-50/70 text-blue-700 border-blue-200/60';
                            $genderIcon  = 'fa-mars';
                            $genderLabel = 'Laki-laki';
                        } elseif ($gender === 'P') {
                            $genderClass = 'bg-pink-50/70 text-pink-700 border-pink-200/60';
                            $genderIcon  = 'fa-venus';
                            $genderLabel = 'Perempuan';
                        } else {
                            $genderClass = 'bg-slate-50 text-slate-600 border-slate-200/60';
                            $genderIcon  = 'fa-venus-mars';
                            $genderLabel = '-';
                        }

                        $isVerified = !is_null($teacher->email_verified_at);
                    @endphp

                    <div class="relative bg-white rounded-2xl border-t-4 border-t-primary border-x border-b border-slate-200/80 p-5 flex flex-col gap-4 shadow-sm group">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary text-white flex flex-col items-center justify-center font-black text-sm shrink-0 shadow-sm shadow-primary/20 tracking-wider">
                                {{ strtoupper(substr($teacher->name, 0, 2)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="font-extrabold text-slate-900 text-base block truncate w-full group-hover:text-primary transition-colors">
                                    {{ $teacher->name }}
                                </span>

                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">NIP.</span>
                                    <span class="font-mono font-bold text-slate-700 text-xs bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ $teacher->teacher->nip ?? '⚠️ BELUM DIATUR' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/60 border border-slate-100 rounded-xl p-3 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400 font-medium">E-Mail Resmi:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[180px]">{{ $teacher->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400 font-medium">No. Telepon:</span>
                                <span class="font-semibold text-slate-600 font-mono">{{ $teacher->teacher->telp ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                                <span class="text-slate-400 font-medium">Status Akun:</span>
                                @if($isVerified)
                                    <span class="text-emerald-700 font-bold flex items-center gap-1 text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> AKTIF
                                    </span>
                                @else
                                    <span class="text-amber-700 font-bold flex items-center gap-1 text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> BELUM AKTIF
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 pt-2 w-full">
                            <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="inline-flex justify-center items-center gap-1.5 py-2.5 bg-slate-100 hover:bg-primary hover:text-white text-slate-700 rounded-xl text-xs font-bold border border-slate-200 transition-all">
                                <i class="fa-solid fa-address-card text-[11px]"></i> Profil
                            </a>
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="inline-flex justify-center items-center gap-1.5 py-2.5 bg-slate-100 hover:bg-amber-500 hover:text-white text-slate-700 rounded-xl text-xs font-bold border border-slate-200 transition-all">
                                <i class="fa-solid fa-pen-to-square text-[11px]"></i> Edit
                            </a>
                            <form id="delete-teacher-mobile-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="w-full inline-block" data-confirm="Akun guru &quot;{{ $teacher->name }}&quot; akan dihapus secara permanen." data-confirm-title="Hapus Guru?" data-confirm-btn="Ya, Hapus">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center gap-1.5 py-2.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold border border-red-200 transition-all">
                                    <i class="fa-solid fa-trash-can text-[11px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center flex flex-col items-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3">
                            <i class="fa-solid fa-users-slash text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-600">Belum ada data Guru</p>
                    </div>
                @endforelse
            </div>


            <div class="hidden md:block bg-white rounded-2xl border-t-4 border-t-primary border-x border-b border-slate-200 shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase tracking-wider select-none">
                                <th class="py-4 px-6 w-[40%]">Data Induk Guru</th>
                                <th class="py-4 px-6 w-[25%]">Email / Telepon</th>
                                <th class="py-4 px-6 text-center w-[15%]">Gender</th>
                                <th class="py-4 px-6 text-center w-[10%]">Status</th>
                                <th class="py-4 px-6 text-right pr-8 w-[10%]">Kelola</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60">
                            @forelse ($teachers as $teacher)
                                @php
                                    $gender = $teacher->teacher->gender ?? '';

                                    if ($gender === 'L') {
                                        $genderClass = 'bg-blue-50 text-blue-700 border-blue-200/60';
                                        $genderIcon  = 'fa-mars';
                                        $genderLabel = 'Laki-Laki';
                                    } elseif ($gender === 'P') {
                                        $genderClass = 'bg-pink-50 text-pink-700 border-pink-200/60';
                                        $genderIcon  = 'fa-venus';
                                        $genderLabel = 'Perempuan';
                                    } else {
                                        $genderClass = 'bg-slate-50 text-slate-500 border-slate-200/60';
                                        $genderIcon  = 'fa-venus-mars';
                                        $genderLabel = 'Kosong';
                                    }

                                    $isVerified = !is_null($teacher->email_verified_at);
                                @endphp
                                <tr class="hover:bg-primary/[0.01] transition-colors duration-150 group">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-xl bg-primary text-white flex items-center justify-center font-black text-sm shrink-0 shadow-sm shadow-primary/20 tracking-wider">
                                                {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="font-extrabold text-slate-900 text-sm group-hover:text-primary transition-colors block truncate max-w-xs">
                                                    {{ $teacher->name }}
                                                </span>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-[9px] text-slate-400 font-bold tracking-widest uppercase">NIP</span>
                                                    <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200/80 px-1.5 py-0.5 rounded">
                                                        {{ $teacher->teacher->nip ?? 'Belum Diisi' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex flex-col gap-1 text-xs">
                                            <span class="text-slate-800 font-medium flex items-center gap-2">
                                                <i class="fa-solid fa-envelope opacity-50 text-primary text-[11px] w-3.5"></i>{{ $teacher->email }}
                                            </span>
                                            <span class="text-slate-500 font-mono flex items-center gap-2">
                                                <i class="fa-solid fa-phone opacity-40 text-slate-400 text-[10px] w-3.5"></i>{{ $teacher->teacher->telp ?? '-' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border {{ $genderClass }} select-none">
                                            <i class="fa-solid {{ $genderIcon }} text-[11px]"></i> {{ $genderLabel }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-center">
                                        @if($isVerified)
                                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/60 px-2.5 py-0.5 rounded-md text-[10px] font-black tracking-wide uppercase select-none">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-md text-[10px] font-black tracking-wide uppercase select-none">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pasif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 whitespace-nowrap text-right pr-8">
                                        <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden shadow-xs bg-white">
                                            <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="w-9 h-9 inline-flex items-center justify-center text-slate-500 hover:text-primary hover:bg-slate-50 border-r border-slate-200/80 transition-all" title="Lihat Profil">
                                                <i class="fa-solid fa-id-card text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="w-9 h-9 inline-flex items-center justify-center text-slate-500 hover:text-amber-600 hover:bg-slate-50 border-r border-slate-200/80 transition-all" title="Ubah Data">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form id="delete-teacher-desktop-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="inline-block" data-confirm="Akun guru &quot;{{ $teacher->name }}&quot; akan dihapus secara permanen." data-confirm-title="Hapus Guru?" data-confirm-btn="Ya, Hapus">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-9 h-9 inline-flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Hapus Akun">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mb-3 border border-slate-100">
                                                <i class="fa-solid fa-folder-open text-2xl"></i>
                                            </div>
                                            <p class="text-sm font-extrabold text-slate-700">Database Kosong</p>
                                            @if($search)
                                                <p class="text-xs text-slate-400 mt-1">Data guru dengan kata kunci "{{ $search }}" tidak ditemukan.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $teachers->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
