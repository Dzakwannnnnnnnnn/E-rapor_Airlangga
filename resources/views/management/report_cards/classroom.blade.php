@extends('layouts.dashboard')

@section('title', 'Detail Pengesahan – ' . $classroom->name . ' · e-Rapor')

@section('back_url', route('admin.report-cards.index'))

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

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                <i class="fa-solid fa-stamp"></i>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Pengesahan Kelas {{ $classroom->name }}</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-secondary"></i>
                        <span>Jurusan: <strong>{{ $classroom->major }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-secondary"></i>
                        <span>Wali: <strong>{{ $classroom->homeroomTeacher ? $classroom->homeroomTeacher->user->name : 'Belum Ditentukan' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16">

    {{-- Navigation Back --}}
    <div class="-mt-16 relative z-20">
        <a href="{{ route('admin.report-cards.index') }}"
           class="flex sm:inline-flex items-center gap-3 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase block">Kembali ke Pusat Pengesahan</span>
            </div>
        </a>
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

    {{-- Student Report Cards Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Status Pengajuan Rapor Siswa</h4>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Semester {{ ucfirst($activeYear->semester) }} | {{ $activeYear->year }}</p>
            </div>

            {{-- Bulk Sahkan Rapor --}}
            <form action="{{ route('admin.report-cards.validate-all', $classroom->id) }}" method="POST" class="inline"
                  data-confirm-title="Sahkan Semua Rapor"
                  data-confirm="Apakah Anda yakin ingin mengesahkan seluruh rapor siswa yang sudah diajukan di kelas {{ $classroom->name }}? Rapor yang disahkan akan langsung dapat diakses oleh masing-masing Orang Tua."
                  data-confirm-btn="Ya, Sahkan Semua"
                  data-confirm-type="warning">
                @csrf
                <button type="submit" 
                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-600/10 cursor-pointer flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-stamp"></i> Sahkan Semua
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Siswa</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">Rata-Rata</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-20">Rank</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-56">Catatan Wali Kelas</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-36">Status</th>
                        <th class="text-right px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-[220px]">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($students as $index => $student)
                    @php
                        $rc = $reportCards->get($student->id);
                        $statusText = 'Belum Dibuat';
                        $statusStyle = 'text-slate-400 bg-slate-50 border-slate-200';
                        
                        if ($rc) {
                            if ($rc->is_validated) {
                                $statusText = 'Disahkan';
                                $statusStyle = 'text-emerald-700 bg-emerald-50 border-emerald-100';
                            } elseif ($rc->is_submitted) {
                                $statusText = 'Butuh Pengesahan';
                                $statusStyle = 'text-blue-700 bg-blue-50 border-blue-100';
                            } elseif ($rc->final_score !== null) {
                                $statusText = 'Draft (Belum Kirim)';
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

                        <td class="px-4 py-4 text-center font-bold text-slate-700">
                            {{ $rc && $rc->final_score !== null ? number_format($rc->final_score, 2) : '–' }}
                        </td>

                        <td class="px-4 py-4 text-center font-bold text-slate-700 text-xs">
                            {{ $rc && $rc->rank !== null ? '#' . $rc->rank : '–' }}
                        </td>

                        <td class="px-4 py-4 text-xs text-slate-600 font-medium leading-relaxed italic">
                            {{ $rc ? ($rc->description ?: 'Tidak ada catatan.') : '–' }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border text-[9px] font-black uppercase shrink-0 {{ $statusStyle }}">
                                {{ $statusText }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($rc && $rc->is_submitted && !$rc->is_validated)
                                    {{-- Sahkan --}}
                                    <form id="validate-form-{{ $rc->id }}" action="{{ route('admin.report-cards.validate', $rc->id) }}" method="POST" class="inline"
                                          data-confirm-title="Sahkan Rapor Siswa"
                                          data-confirm="Apakah Anda yakin ingin mengesahkan rapor untuk {{ $student->name }}? Rapor yang disahkan akan langsung dapat diakses oleh Orang Tua / Wali Murid."
                                          data-confirm-btn="Ya, Sahkan"
                                          data-confirm-type="warning">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold uppercase transition shadow-sm">
                                            <i class="fa-solid fa-stamp mr-1"></i> Sahkan
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <form id="reject-form-{{ $rc->id }}" action="{{ route('admin.report-cards.reject', $rc->id) }}" method="POST" class="inline"
                                          data-confirm-title="Kembalikan Rapor (Tolak)"
                                          data-confirm="Apakah Anda yakin ingin menolak pengajuan rapor untuk {{ $student->name }}? Status rapor akan kembali menjadi Draft di pihak Wali Kelas."
                                          data-confirm-btn="Ya, Kembalikan"
                                          data-confirm-type="danger">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase transition shadow-sm">
                                            <i class="fa-solid fa-circle-left mr-1"></i> Tolak
                                        </button>
                                    </form>
                                @elseif($rc && $rc->is_validated)
                                    {{-- Revert validated to draft if needed --}}
                                    <form id="reject-form-{{ $rc->id }}" action="{{ route('admin.report-cards.reject', $rc->id) }}" method="POST" class="inline"
                                          data-confirm-title="Batalkan Pengesahan Rapor"
                                          data-confirm="Apakah Anda yakin ingin membatalkan pengesahan rapor untuk {{ $student->name }}? Akses Orang Tua akan ditutup kembali."
                                          data-confirm-btn="Ya, Batalkan"
                                          data-confirm-type="danger">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 text-[10px] font-bold uppercase transition">
                                            <i class="fa-solid fa-rotate-left mr-1"></i> Batalkan Sah
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold uppercase pr-2">Belum Diajukan</span>
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
