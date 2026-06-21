@extends('layouts.dashboard')

@section('title', 'Detail Laporan Nilai Rapor – e-Rapor')

@section('back_url', route('teacher.laporan.index'))

@section('content')

{{-- ─── Hero Banner ─── --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)<div class="w-1.5 h-1.5 bg-white rounded-full"></div>@endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                <i class="fa-solid fa-list-ol"></i>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Pengolahan Nilai &amp; Deskripsi</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-secondary"></i>
                        <span>Kelas: <strong>{{ $assignment->classroom->name }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-book text-secondary"></i>
                        <span>Mapel: <strong>{{ $assignment->subject->name }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar text-secondary"></i>
                        <span>Sem: <strong>{{ ucfirst($assignment->academicYear->semester ?? '') }}</strong> ({{ $assignment->academicYear->year ?? '' }})</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-6 space-y-6 px-4 md:px-0 pb-16 print:mt-0 print:pb-0">

    {{-- Navigation Back (hidden on print) --}}
    <div class="-mt-16 relative z-20 print:hidden">
        <a href="{{ route('teacher.laporan.index') }}"
           class="flex sm:inline-flex items-center gap-3 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase block">Kembali ke Laporan</span>
            </div>
        </a>
    </div>

    {{-- Locked Status Alert --}}
    @if($isSubmitted)
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-lock text-sm"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-emerald-800 text-base">Nilai Rapor Telah Dikirim &amp; Dikunci</h4>
                <p class="text-xs text-emerald-700 mt-1 font-medium">
                    Nilai dan deskripsi mata pelajaran ini telah dikirim ke Wali Kelas. Anda tidak dapat melakukan perubahan data lagi.
                </p>
            </div>
        </div>
        <div class="shrink-0 self-end sm:self-center">
            <form id="cancel-submission-form" action="{{ route('teacher.laporan.cancel-submission', $assignment->id) }}" method="POST"
                  data-confirm-title="Batalkan Pengiriman Nilai"
                  data-confirm="Apakah Anda yakin ingin membatalkan pengiriman nilai ini ke Wali Kelas? Kunci pengisian nilai akan dibuka kembali sehingga Anda bisa mengubahnya."
                  data-confirm-btn="Ya, Batalkan"
                  data-confirm-type="warning">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-black uppercase tracking-wider transition shadow-md shadow-amber-500/20">
                    <i class="fa-solid fa-unlock mr-1.5"></i> Batalkan Pengiriman
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- KKM Info Card (hidden on print) --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-calculator text-sm"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-slate-800">Batas Ketuntasan Minimal (KKM): <span class="text-amber-600 font-extrabold text-sm">{{ $kkm }}</span></h5>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">KKM berlaku sama untuk mata pelajaran {{ $assignment->subject->name }} di semua kelas.</p>
            </div>
        </div>
        <div class="text-right text-[10px] text-slate-400 font-semibold">
            Status KKM ditetapkan melalui menu <a href="{{ route('teacher.kelas_saya.index') }}" class="text-primary hover:underline font-bold">Kelas Saya</a>.
        </div>
    </div>

    {{-- ─── MAIN ACTIONS PANEL (hidden on print) ─── --}}
    @if(!$isSubmitted)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4 print:hidden">
        <div class="flex items-center gap-3">
            <form id="regenerate-desc-form" action="{{ route('teacher.laporan.regenerate', $assignment->id) }}" method="POST" class="inline"
                  data-confirm-title="Regenerate Deskripsi"
                  data-confirm="Apakah Anda yakin ingin mengatur ulang dan mengisi otomatis seluruh deskripsi berdasarkan data nilai? Deskripsi yang Anda ketik manual akan tertimpa."
                  data-confirm-btn="Ya, Regenerate"
                  data-confirm-type="warning">
                @csrf
                <button type="submit" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-black uppercase tracking-wider transition shadow-sm shrink-0">
                    <i class="fa-solid fa-arrows-rotate mr-1.5"></i> Regenerate Deskripsi
                </button>
            </form>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <button type="button" onclick="submitForm('{{ route('teacher.laporan.save-draft', $assignment->id) }}')" 
                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider transition shrink-0">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Draft
            </button>
            
            <button type="button" onclick="submitForm('{{ route('teacher.laporan.submit-final', $assignment->id) }}', 'Apakah Anda yakin ingin mengirim nilai ini ke Wali Kelas? Nilai dan deskripsi akan dikunci dan TIDAK BISA DIUBAH LAGI setelah dikirim.')" 
                    class="px-5 py-2.5 rounded-xl bg-primary hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition shadow-md shadow-primary/20 shrink-0">
                <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim ke Wali Kelas
            </button>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center justify-between print:hidden">
        <span class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-info-circle mr-1 text-primary"></i> Gunakan tombol cetak/leger untuk mendownload file laporan.</span>
        <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider transition">
            <i class="fa-solid fa-print mr-1.5"></i> Cetak / Leger Nilai
        </button>
    </div>
    @endif

    {{-- ─── TABLE DATA ─── --}}
    <form id="grades-form" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden print:border-0 print:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm print:text-[10px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 print:bg-white print:border-b-2 print:border-slate-800">
                            <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                            <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-48">Siswa</th>
                            <th class="text-center px-3 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-28">Nilai Rapor</th>
                            <th class="text-center px-3 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">Status</th>
                            <th class="text-left px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Capaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 print:divide-y-0 print:divide-b">
                        @foreach($previews as $index => $p)
                        @php
                            $student = $p['student'];
                            $final = $p['final'];
                            $predikat = $p['predikat'];
                            $isSaved = $p['is_saved'];
                            
                            $scoreColor = ($final !== null && $final >= $kkm) ? 'text-emerald-700' : 'text-rose-700';
                            
                            $predColor = match($predikat) {
                                'A' => 'text-emerald-700 bg-emerald-50 border border-emerald-100',
                                'B' => 'text-blue-700 bg-blue-50 border border-blue-100',
                                'C' => 'text-amber-700 bg-amber-50 border border-amber-100',
                                'D' => 'text-rose-700 bg-rose-50 border border-rose-100',
                                default => 'text-slate-400 bg-slate-50 border border-slate-100'
                            };
                        @endphp
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/20' }} hover:bg-blue-50/10 transition-colors print:bg-white">
                            <td class="px-4 py-4 text-[11px] font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <td class="px-4 py-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $student->name }}</div>
                                <div class="text-[10px] font-semibold text-slate-400 mt-0.5">NISN: {{ $student->nisn }}</div>
                            </td>
                            
                            <td class="px-3 py-4 text-center">
                                @if($final !== null)
                                    <span class="font-black text-base {{ $scoreColor }}">{{ number_format($final, 2) }}</span>
                                    <span class="inline-block ml-1 px-1.5 py-0.5 rounded text-[10px] font-black {{ $predColor }}">{{ $predikat }}</span>
                                    <input type="hidden" name="grades[{{ $student->id }}][final_score]" value="{{ $final }}">
                                @else
                                    <span class="text-slate-300 font-bold text-sm">–</span>
                                    <input type="hidden" name="grades[{{ $student->id }}][final_score]" value="">
                                @endif
                            </td>
                            
                            <td class="px-3 py-4 text-center">
                                @if($final !== null)
                                    @if($final >= $kkm)
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                            Tuntas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
                                            Remedial
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-100">
                                        Kosong
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-4">
                                @if(!$isSubmitted)
                                    <textarea name="grades[{{ $student->id }}][description]" rows="3"
                                              class="w-full p-2.5 text-[11px] border border-slate-200 rounded-xl focus:outline-none focus:border-primary font-medium leading-relaxed"
                                              placeholder="Tulis capaian kompetensi...">{{ $p['description'] }}</textarea>
                                @else
                                    <div class="text-[11px] text-slate-600 font-medium leading-relaxed italic print:not-italic">
                                        {{ $p['description'] ?: 'Tidak ada deskripsi.' }}
                                    </div>
                                    <input type="hidden" name="grades[{{ $student->id }}][description]" value="{{ $p['description'] }}">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Actions Below Table (hidden on print) --}}
        @if(!$isSubmitted)
        <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-5 rounded-2xl print:hidden">
            <span class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-circle-info text-primary mr-1"></i> Jangan lupa menyimpan perubahan nilai dan deskripsi sebelum berpindah halaman.</span>
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" onclick="submitForm('{{ route('teacher.laporan.save-draft', $assignment->id) }}')" 
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-black uppercase tracking-wider transition shrink-0">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Draft
                </button>
                
                <button type="button" onclick="submitForm('{{ route('teacher.laporan.submit-final', $assignment->id) }}', 'Apakah Anda yakin ingin mengirim nilai ini ke Wali Kelas? Nilai dan deskripsi akan dikunci dan TIDAK BISA DIUBAH LAGI setelah dikirim.')" 
                        class="px-5 py-2.5 rounded-xl bg-primary hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition shadow-md shadow-primary/20 shrink-0">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim ke Wali Kelas
                </button>
            </div>
        </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    function submitForm(actionUrl, confirmMsg = null) {
        const form = document.getElementById('grades-form');
        form.action = actionUrl;

        if (confirmMsg) {
            window.showConfirm({
                title: 'Kirim Nilai ke Wali Kelas',
                message: confirmMsg,
                confirmText: 'Ya, Kirim',
                formId: 'grades-form',
                type: 'warning'
            });
        } else {
            form.submit();
        }
    }
</script>
@endpush

@endsection
