@extends('layouts.dashboard')

@section('title', 'Wali Kelas – ' . $classroom->name . ' · e-Rapor')

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
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Kelola Rapor Kelas</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                            <span>Kelas: <strong>{{ $classroom->name }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-base text-secondary opacity-95"></i>
                            <span>{{ $classroom->major }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16">

    {{-- Year / Context Card --}}
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 flex flex-col sm:flex-row items-center justify-between gap-4 -mt-16 relative z-20">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-calendar-check text-base text-secondary"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-slate-800">Tahun Ajaran Aktif: <span class="text-primary font-extrabold text-sm">{{ $activeYear->year }}</span></h5>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Semester {{ ucfirst($activeYear->semester) }} | Akses Administrasi Wali Kelas</p>
            </div>
        </div>
        <div class="text-xs text-slate-500 font-semibold">
            Total Siswa: <strong class="text-slate-800">{{ $students->count() }} Orang</strong>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div id="flash-error" class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl px-4 py-3.5 text-sm font-semibold shadow-sm">
            <i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>
            {{ session('error') }}
            <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-400 hover:text-rose-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- ─── STUDENT LIST TABLE ─── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-users text-primary"></i>
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Daftar Anggota Kelas</h4>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Bulk Generate Rapor --}}
                <form action="{{ route('teacher.homeroom.generate-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-indigo-600/10 cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-calculator"></i> Hitung Semua Rapor
                    </button>
                </form>

                {{-- Bulk Submit Rapor --}}
                <form action="{{ route('teacher.homeroom.submit-all') }}" method="POST" class="inline"
                      data-confirm-title="Ajukan Semua Rapor"
                      data-confirm="Apakah Anda yakin ingin mengajukan seluruh rapor siswa yang sudah di-generate ke Admin?"
                      data-confirm-btn="Ya, Ajukan Semua"
                      data-confirm-type="warning">
                    @csrf
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-600/10 cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-paper-plane"></i> Ajukan Semua
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Siswa</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-28">Rata-Rata</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">Peringkat</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-32">Status Rapor</th>
                        <th class="text-right px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-[380px]">Aksi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $index => $student)
                    @php
                        $rc = $reportCards->get($student->id);
                        $statusText = 'Belum Diproses';
                        $statusStyle = 'text-slate-500 bg-slate-100 border-slate-200';
                        
                        if ($rc) {
                            if ($rc->is_validated) {
                                $statusText = 'Disahkan Admin';
                                $statusStyle = 'text-emerald-700 bg-emerald-50 border-emerald-100';
                            } elseif ($rc->is_submitted) {
                                $statusText = 'Menunggu Pengesahan';
                                $statusStyle = 'text-blue-700 bg-blue-50 border-blue-100';
                            } elseif ($rc->final_score !== null) {
                                $statusText = 'Draft Rapor';
                                $statusStyle = 'text-amber-700 bg-amber-50 border-amber-100';
                            }
                        }
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/20' }} hover:bg-blue-50/10 transition-colors">
                        <td class="px-4 py-4 text-[11px] font-bold text-slate-400">{{ $index + 1 }}</td>
                        
                        <td class="px-4 py-4">
                            <div class="font-bold text-slate-800 text-xs">{{ $student->name }}</div>
                            <div class="text-[10px] font-semibold text-slate-400 mt-0.5">NISN: {{ $student->nisn }}</div>
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($rc && $rc->final_score !== null)
                                <span class="font-black text-sm text-slate-800">{{ number_format($rc->final_score, 2) }}</span>
                            @else
                                <span class="text-slate-300 font-bold">–</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($rc && $rc->rank !== null)
                                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-black">#{{ $rc->rank }}</span>
                            @else
                                <span class="text-slate-300 font-bold">–</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase shrink-0 {{ $statusStyle }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- 1. Monitor --}}
                                <a href="{{ route('teacher.homeroom.student.pantau', $student->id) }}" 
                                   class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-[10px] font-bold uppercase transition"
                                   title="Pantau Semua Nilai Mapel">
                                    <i class="fa-solid fa-list mr-1"></i> Pantau
                                </a>

                                {{-- 2. Input Notes & Absen --}}
                                @if(!$rc || !$rc->is_validated)
                                <a href="{{ route('teacher.homeroom.student.input', $student->id) }}" 
                                   class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-[10px] font-bold uppercase transition"
                                   title="Input Absen & Catatan">
                                    <i class="fa-solid fa-user-pen mr-1"></i> Catatan
                                </a>
                                @endif

                                @if(!$rc || !$rc->is_validated)
                                    {{-- 3. Generate Rapor --}}
                                    <form action="{{ route('teacher.homeroom.student.generate', $student->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase transition shadow-sm"
                                                title="Hitung rata-rata dan peringkat siswa">
                                            <i class="fa-solid fa-calculator mr-1"></i> Hitung Rapor
                                        </button>
                                    </form>

                                    {{-- 4. Submit to Admin --}}
                                    @if($rc && $rc->final_score !== null && !$rc->is_submitted)
                                        <form id="submit-form-{{ $student->id }}" action="{{ route('teacher.homeroom.student.submit', $student->id) }}" method="POST" class="inline"
                                              data-confirm-title="Ajukan Pengesahan Rapor"
                                              data-confirm="Apakah Anda yakin ingin mengajukan pengesahan rapor untuk {{ $student->name }} ke Admin? Rapor akan dikunci sementara menunggu persetujuan Admin."
                                              data-confirm-btn="Ya, Ajukan"
                                              data-confirm-type="warning">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold uppercase transition shadow-sm"
                                                    title="Ajukan pengesahan rapor ke Admin">
                                                <i class="fa-solid fa-paper-plane mr-1"></i> Ajukan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- 5. Cancel submission --}}
                                    @if($rc && $rc->is_submitted)
                                        <form id="cancel-form-{{ $student->id }}" action="{{ route('teacher.homeroom.student.cancel', $student->id) }}" method="POST" class="inline"
                                              data-confirm-title="Batalkan Pengajuan Rapor"
                                              data-confirm="Apakah Anda yakin ingin membatalkan pengajuan rapor untuk {{ $student->name }}? Rapor akan kembali ke status Draft."
                                              data-confirm-btn="Ya, Batalkan"
                                              data-confirm-type="danger">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase transition shadow-sm"
                                                    title="Batalkan pengajuan ke Admin">
                                                <i class="fa-solid fa-ban mr-1"></i> Batal Ajukan
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 rounded px-2.5 py-1 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> Rapor Sah
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
