@extends('layouts.dashboard')

@section('title', 'Daftar Penugasan Mengajar - ' . $user->name . ' - e-Rapor')

@section('back_url', route('admin.assignments.teachers'))

@section('content')
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">

        {{-- Background Geometric Decoration --}}
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        {{-- Hero Content --}}
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-id-card text-[9px]"></i> NIP: {{ $teacher->nip ?? '-' }}
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Daftar Penugasan Mengajar</h3>
                <h4 class="text-sm md:text-base font-semibold text-white/90 mt-0.5 uppercase tracking-wide">{{ $user->name }}</h4>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                        <span>Panel Kontrol Kependidikan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-base text-secondary opacity-95"></i>
                        <span>Tahun Ajaran Aktif: {{ $activeYear->year ?? '-' }} ({{ ($activeYear->semester ?? 1) == 1 ? 'Ganjil' : 'Genap' }})</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Wave Path --}}
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6 pb-12">

    {{-- Floating Navigation and Actions --}}
    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0 flex flex-col sm:flex-row gap-3 items-center justify-between">
        {{-- Back Button --}}
        <a href="{{ route('admin.assignments.teachers') }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Daftar Guru</span>
            </div>
        </a>

        {{-- Add Assignment Button --}}
        <a href="{{ route('admin.teachers.assignments.create', $teacher->id) }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-primary text-white rounded-2xl shadow-md p-3 hover:bg-primary/90 hover:shadow-primary/20 transition-all group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-plus text-sm animate-pulse"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-4">
                <span class="text-[9px] font-bold text-white/70 uppercase tracking-widest leading-none mb-1 block">Aksi</span>
                <span class="text-xs font-black uppercase leading-none block truncate">Tambah Penugasan</span>
            </div>
        </a>
    </div>

    {{-- Main Content Layout --}}
    <div class="px-4 sm:px-0 space-y-4">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Daftar Penugasan Mengajar</h4>

        @if($assignments->isEmpty())
            <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center shadow-sm select-none max-w-2xl mx-auto mt-6">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h5 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Data Penugasan Kosong</h5>
                <p class="text-xs text-slate-400 mt-1 leading-normal max-w-sm mx-auto">Pendidik ini belum memiliki riwayat tugas mengajar di kelas maupun mata pelajaran apapun.</p>
                <a href="{{ route('admin.teachers.assignments.create', $teacher->id) }}"
                   class="mt-6 inline-flex items-center gap-2 bg-primary text-white text-xs font-black uppercase tracking-wider px-5 py-2.5 rounded-xl shadow-md shadow-primary/20 hover:bg-opacity-90 transition-all cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Tambah Penugasan Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($assignments as $academicYearId => $group)
                    @php
                        $firstGroup = $group->first();
                        $isCurrentActive = $activeYear && $activeYear->id == $academicYearId;
                    @endphp
                        {{-- Header Kelompok Tahun Ajaran --}}
                        <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between {{ $isCurrentActive ? 'bg-primary/[0.02]' : 'bg-slate-50/50' }} border-b border-slate-100 gap-3 sm:gap-4 select-none">

                            {{-- Sisi Kiri: Tahun Ajaran & Status Badge --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-black text-slate-800 text-xs md:text-sm uppercase tracking-wide whitespace-nowrap">
                                    TA {{ $firstGroup->academicYear->year }} — {{ $firstGroup->academicYear->semester == 1 ? 'Ganjil' : 'Genap' }}
                                </span>
                                @if($isCurrentActive)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-secondary text-slate-950 shadow-sm">
                                        <span class="w-1 h-1 rounded-full bg-slate-950 animate-ping"></span> Aktif
                                    </span>
                                @endif
                            </div>

                            {{-- Sisi Kanan: Jumlah Kelas --}}
                            <div class="self-start sm:self-center shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-slate-400 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200/60 uppercase tracking-wider shadow-xs">
                                    <i class="fa-solid fa-layer-group text-[9px] text-slate-400/80"></i> {{ $group->count() }} Kelas
                                </span>
                            </div>

                        </div>

                        {{-- Tabel Item Penugasan --}}
                        <div class="p-4 sm:p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($group as $assignment)
                                <div class="group/item flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-slate-50/60 border border-slate-100/80 rounded-xl transition-all duration-200 gap-3 sm:gap-4 shadow-sm hover:shadow-md">

                                    {{-- Bagian Kiri: Info Kelas & Mata Pelajaran --}}
                                    <div class="flex items-start sm:items-center gap-3 sm:gap-4 flex-1 min-w-0 w-full">
                                        {{-- Inisial Kelas Efek Avatar --}}
                                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-primary/5 text-primary group-hover/item:bg-primary group-hover/item:text-white flex items-center justify-center font-black text-xs sm:text-sm shrink-0 select-none transition-colors duration-200 shadow-sm border border-primary/10 mt-0.5 sm:mt-0">
                                            {{ substr($assignment->classroom->name, 0, 2) }}
                                        </div>

                                        {{-- Detail Penugasan --}}
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[9px] sm:text-[10px] font-black text-primary uppercase tracking-widest bg-primary/10 px-2 py-0.5 rounded-md inline-block">
                                                    {{ $assignment->classroom->name }}
                                                </span>
                                            </div>
                                            <h5 class="font-black text-slate-800 text-sm md:text-base leading-snug break-words sm:truncate">
                                                {{ $assignment->subject->name }}
                                            </h5>
                                        </div>
                                    </div>

                                    {{-- Bagian Kanan: Tombol Aksi --}}
                                    <div class="flex items-center shrink-0 w-full sm:w-auto pt-3 sm:pt-0 mt-2 sm:mt-0 border-t border-slate-100 border-dashed sm:border-none">
                                        <form action="{{ route('admin.teachers.assignments.destroy', [$teacher->id, $assignment->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan mengajar di kelas {{ $assignment->classroom->name }} ini?');"
                                              class="w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-[10px] font-black text-danger uppercase tracking-wider bg-red-50 hover:bg-red-100 text-center rounded-xl transition-all cursor-pointer flex items-center justify-center gap-1.5 border border-red-100">
                                                <i class="fa-solid fa-trash-can text-[9px]"></i> Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
