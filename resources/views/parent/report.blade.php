@extends('layouts.dashboard')

@section('title', 'Laporan Hasil Belajar - e-Rapor')
@section('back_url', route('parent.dashboard'))

@section('content')

@php
    $studentName   = $student ? $student->name : '';
    $classroom     = $student ? $student->classroom : null;
    $semesterLabel = ($activeYear)
        ? ucfirst($activeYear->semester) . ' ' . $activeYear->year
        : '—';

    // Hitung countdown ke publish date
    $releaseAt   = $activeYear?->report_release_at;
    $now         = now();
    $isReleased  = $releaseAt ? $now->gte($releaseAt) : false;
    $isPublished = $activeReport && $activeReport->is_validated && $isReleased && $allValidated;

    // Sisa waktu menuju rilis
    $countdown = null;
    if (!$isPublished && $releaseAt && !$isReleased) {
        $diff = $now->diff($releaseAt);
        $countdown = [
            'days'    => (int) $now->diffInDays($releaseAt),
            'hours'   => $diff->h,
            'minutes' => $diff->i,
        ];
    }
@endphp

{{-- Hero Header (Kop Dokumen Resmi dengan Penyesuaian Warna & Dekorasi) --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-12 pb-24 px-6 md:px-8 relative overflow-hidden select-none">

        {{-- Elemen Dekoratif Aksen Grafis dari Detail Akademik --}}
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                {{-- Logo / Emblem Dokumen (Kaku Struktural dengan Backdrop Modern) --}}
                <div class="w-20 h-24 md:w-24 md:h-28 bg-white/10 border border-white/20 flex flex-col items-center justify-center shrink-0 shadow-lg relative p-2 backdrop-blur-sm">
                    <i class="fa-solid fa-building-columns text-secondary text-3xl md:text-4xl mb-2"></i>
                    <div class="w-full h-px bg-white/20 mb-1"></div>
                    <div class="w-3/4 h-px bg-white/20"></div>
                    @if($isPublished)
                        <span class="absolute -bottom-3 -right-3 w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center border-2 border-primary shadow-sm" title="Telah Disahkan">
                            <i class="fa-solid fa-check-double text-white text-xs"></i>
                        </span>
                    @endif
                </div>

                <div class="flex-1 text-center md:text-left min-w-0 mt-2">
                    <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-[10px] text-secondary font-bold px-3 py-1 rounded-sm mb-3 uppercase tracking-widest shadow-sm backdrop-blur-sm">
                        <i class="fa-solid fa-bookmark text-[9px]"></i>
                        Tahun Ajaran {{ $semesterLabel }}
                    </div>
                    <h3 class="font-serif font-bold text-2xl md:text-3xl leading-tight uppercase tracking-widest text-white">Laporan Hasil Belajar</h3>
                    <p class="text-white/85 text-xs md:text-sm font-medium mt-2 leading-relaxed max-w-md">
                        Dokumen resmi riwayat akademik dan evaluasi hasil pembelajaran peserta didik atas nama <strong class="text-secondary uppercase">{{ $studentName }}</strong>.
                    </p>
                </div>
            </div>

            {{-- Grade Badge (Stempel Nilai Resmi) --}}
            @if($isPublished && $activeReport)
                <div class="shrink-0 self-center md:self-start w-24 h-24 bg-transparent border-4 border-secondary/60 flex flex-col items-center justify-center shadow-sm opacity-95 transform rotate-[-5deg]">
                    @php
                        $s = (float) $activeReport->final_score;
                        $lbl = $s >= 88 ? 'A' : ($s >= 82 ? 'A-' : ($s >= 78 ? 'B+' : ($s >= 75 ? 'B' : 'C')));
                    @endphp
                    <span class="text-3xl font-black text-secondary leading-none">{{ $lbl }}</span>
                    <span class="w-12 h-px bg-secondary/40 my-1"></span>
                    <span class="text-[9px] font-bold text-secondary/80 uppercase tracking-widest">Predikat</span>
                </div>
            @endif
        </div>

        {{-- Geometri Clip Path di Dasar Header --}}
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

{{-- Content --}}
<div class="max-w-4xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16 relative z-20 -mt-10 md:-mt-12">

    @if(!$student)
        {{-- Siswa Tidak Ditemukan --}}
        <div class="bg-white rounded-md border-t-4 border-amber-600 border border-slate-200 p-10 text-center shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-3xl block mb-3"></i>
            <p class="text-sm font-bold text-slate-800 uppercase tracking-wide">Siswa Tidak Terdaftar</p>
            <p class="text-xs text-slate-500 mt-2">Data peserta didik tidak ditemukan dalam sistem akademik.</p>
        </div>
    @else
        {{-- CARD UTAMA: Dokumen Semester Ini --}}
        <div class="bg-white rounded-md border border-slate-200 shadow-md overflow-hidden">

            {{-- Header Form / Data Diri --}}
            <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white border border-slate-200 text-primary flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-id-card text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-0.5">Identitas Peserta Didik</p>
                        <p class="text-base font-extrabold text-slate-900 uppercase tracking-wide">{{ $studentName }}</p>
                        <p class="text-xs text-slate-600 mt-0.5">
                            Kelas: <span class="font-bold text-primary">{{ $classroom?->name ?? '—' }}</span> <span class="mx-1 text-slate-300">|</span> NISN: <span class="font-bold text-slate-700">{{ $student->nisn ?? '—' }}</span>
                        </p>
                    </div>
                </div>
                <div class="shrink-0 self-start md:self-center">
                    @if($activeYear)
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-bold px-3 py-1.5 rounded-sm uppercase tracking-widest border
                            {{ $isPublished ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-amber-50 text-amber-700 border-amber-300' }}">
                            <i class="fa-solid {{ $isPublished ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $isPublished ? 'Dokumen Sah' : 'Menunggu Pengesahan' }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Body Dokumen --}}
            <div class="p-6">
                @if($isPublished && $activeReport)
                    {{-- Grid Statistik Resmi --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="border border-emerald-100 p-4 rounded-sm bg-emerald-50/50 text-center shadow-inner">
                            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-2">Nilai Rata-rata</p>
                            <p class="text-2xl font-black text-emerald-600">{{ number_format((float)$activeReport->final_score, 1) }}</p>
                        </div>
                        <div class="border border-slate-200 p-4 rounded-sm bg-slate-50/50 text-center">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Peringkat Kelas</p>
                            <p class="text-2xl font-black text-slate-800">{{ $activeReport->rank ?? '—' }}</p>
                            <p class="text-[9px] text-slate-500 font-medium mt-1">dari {{ $totalStudents }} Siswa</p>
                        </div>
                        <div class="border border-slate-200 p-4 rounded-sm bg-slate-50/50 text-center md:col-span-2 flex flex-col justify-center items-center">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Keputusan Akhir</p>
                            <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 text-xs font-bold px-4 py-1.5 border border-emerald-300 uppercase tracking-widest">
                                <i class="fa-solid fa-stamp text-sm"></i> LULUS
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('parent.report.view', $activeReport->id) }}"
                       class="w-full flex items-center justify-center gap-3 px-5 py-3 bg-primary hover:bg-opacity-90 text-white font-bold uppercase tracking-widest text-sm rounded-sm transition-all shadow-sm">
                        <i class="fa-solid fa-file-pdf"></i>
                        Buka Dokumen Rapor
                    </a>
                @else
                    {{-- Status Belum Rilis / Draft --}}
                    <div class="bg-slate-50 border border-slate-200 p-6 text-center">
                        <div class="w-12 h-12 bg-white border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-4 rounded-full shadow-sm">
                            <i class="fa-solid fa-file-signature text-lg"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-1">Status Dokumen: Proses Penyusunan</p>

                        @if($activeReport && $activeReport->is_submitted)
                            <p class="text-xs text-slate-600 mb-4">Dokumen telah disubmit oleh Wali Kelas dan sedang menunggu pengesahan Kepala Sekolah.</p>
                        @else
                            <p class="text-xs text-slate-600 mb-4">Dokumen Rapor sedang dalam proses evaluasi dan penginputan nilai.</p>
                        @endif

                        {{-- Countdown Resmi --}}
                        @if($countdown)
                            <div id="countdown-active-wrapper" class="inline-block bg-[#002266] text-white p-6 rounded-2xl shadow-lg border border-white/10 text-left mx-auto max-w-sm w-full relative overflow-hidden">
                                {{-- Background decorative circle --}}
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                                <p class="text-[10px] font-bold text-[#FFB800] uppercase tracking-widest mb-4 border-b border-white/10 pb-2 flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar-check text-xs"></i> Estimasi Penerbitan Dokumen
                                </p>

                                <div class="grid grid-cols-4 gap-2 text-center mb-4" id="countdown-container">
                                    <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                                        <span id="parent-cd-days" class="block text-xl font-black font-mono text-[#FFB800] leading-none">00</span>
                                        <span class="text-[8px] uppercase tracking-wider opacity-60">Hari</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                                        <span id="parent-cd-hours" class="block text-xl font-black font-mono text-[#FFB800] leading-none">00</span>
                                        <span class="text-[8px] uppercase tracking-wider opacity-60">Jam</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                                        <span id="parent-cd-minutes" class="block text-xl font-black font-mono text-[#FFB800] leading-none">00</span>
                                        <span class="text-[8px] uppercase tracking-wider opacity-60">Menit</span>
                                    </div>
                                    <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                                        <span id="parent-cd-seconds" class="block text-xl font-black font-mono text-[#FFB800] leading-none">00</span>
                                        <span class="text-[8px] uppercase tracking-wider opacity-60">Detik</span>
                                    </div>
                                </div>

                                <p class="text-[10px] text-white/70 text-center">
                                    Jadwal: <strong>{{ $releaseAt->format('d F Y, H:i') }} WIB</strong>
                                </p>
                            </div>
                        @elseif($releaseAt && $isReleased && !$isPublished)
                            <div class="inline-block bg-[#002266] text-white p-6 rounded-2xl shadow-lg border border-white/10 text-left mx-auto max-w-sm w-full relative overflow-hidden">
                                {{-- Background decorative circle --}}
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                                <p class="text-[10px] font-bold text-[#FFB800] uppercase tracking-widest mb-3 border-b border-white/10 pb-2 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-info text-xs"></i> Status Rapor
                                </p>

                                <h3 class="text-lg font-black uppercase tracking-tight leading-tight text-[#FFB800] mb-2">
                                    Menunggu Pengesahan
                                </h3>
                                <p class="text-xs text-white/70 font-normal leading-relaxed">
                                    Tenggat rilis telah lewat, namun rapor masih dalam proses pengesahan oleh Kepala Sekolah.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- ARSIP / RIWAYAT RAPOR --}}
        <div class="space-y-4">
            <div class="flex items-center gap-3 border-b-2 border-primary pb-2">
                <i class="fa-solid fa-box-archive text-primary"></i>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-widest">Arsip Laporan Terdahulu</h4>
            </div>

            @if($historyReports->isEmpty())
                <div class="bg-slate-50 border border-slate-200 p-8 text-center rounded-sm">
                    <p class="text-xs text-slate-500 uppercase tracking-widest">Arsip Tidak Ditemukan</p>
                </div>
            @else
                <div class="bg-white border border-slate-300 rounded-sm shadow-sm">
                    {{-- Header Tabel Arsip --}}
                    <div class="hidden md:flex bg-slate-50 border-b border-slate-200 px-6 py-3 text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                        <div class="flex-1">Semester / Tahun Ajaran</div>
                        <div class="w-32 text-center">Status</div>
                        <div class="w-24 text-center">Predikat</div>
                        <div class="w-12 text-right">Aksi</div>
                    </div>

                    <div class="divide-y divide-slate-200">
                        @foreach($historyReports as $hist)
                            @php
                                $histSem   = $hist->academicYear;
                                $histLabel = $histSem ? ucfirst($histSem->semester) . ' ' . $histSem->year : '—';
                                $histScore = (float) ($hist->final_score ?? 0);
                                $histLbl   = $histScore >= 88 ? 'A' : ($histScore >= 82 ? 'A-' : ($histScore >= 78 ? 'B+' : ($histScore >= 75 ? 'B' : 'C')));
                            @endphp

                            @if($hist->is_validated)
                                <a href="{{ route('parent.report.view', $hist->id) }}" class="flex flex-col md:flex-row md:items-center px-6 py-4 hover:bg-slate-50/80 transition-colors group">
                            @else
                                <div class="flex flex-col md:flex-row md:items-center px-6 py-4 opacity-60 bg-slate-50/50">
                            @endif

                                {{-- Kolom Nama Semester --}}
                                <div class="flex-1 flex items-center gap-3 mb-2 md:mb-0">
                                    <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center shrink-0 bg-white shadow-sm">
                                        <i class="fa-solid fa-file-contract text-xs {{ $hist->is_validated ? 'text-primary' : 'text-slate-400' }}"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-primary transition-colors">{{ $histLabel }}</p>
                                    </div>
                                </div>

                                {{-- Kolom Status --}}
                                <div class="md:w-32 md:text-center mb-2 md:mb-0">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest {{ $hist->is_validated ? 'text-emerald-700' : 'text-slate-500' }}">
                                        @if($hist->is_validated)
                                            <i class="fa-solid fa-check"></i> Sah
                                        @else
                                            Draft
                                        @endif
                                    </span>
                                </div>

                                {{-- Kolom Predikat --}}
                                <div class="md:w-24 md:text-center mb-2 md:mb-0">
                                    @if($hist->is_validated)
                                        <span class="text-sm font-black text-slate-800">{{ $histLbl }}</span>
                                    @else
                                        <span class="text-sm font-bold text-slate-400">—</span>
                                    @endif
                                </div>

                                {{-- Kolom Aksi --}}
                                <div class="md:w-12 text-left md:text-right">
                                    @if($hist->is_validated)
                                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 group-hover:text-primary transition-colors"></i>
                                    @else
                                        <i class="fa-solid fa-lock text-slate-300"></i>
                                    @endif
                                </div>

                            @if($hist->is_validated)
                                </a>
                            @else
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

@if($releaseAt && !$isReleased)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const targetDate = new Date("{{ $releaseAt->toIso8601String() }}").getTime();

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                window.location.reload();
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const dEl = document.getElementById("parent-cd-days");
            const hEl = document.getElementById("parent-cd-hours");
            const mEl = document.getElementById("parent-cd-minutes");
            const sEl = document.getElementById("parent-cd-seconds");

            if (dEl) dEl.innerText = days.toString().padStart(2, '0');
            if (hEl) hEl.innerText = hours.toString().padStart(2, '0');
            if (mEl) mEl.innerText = minutes.toString().padStart(2, '0');
            if (sEl) sEl.innerText = seconds.toString().padStart(2, '0');
        }, 1000);
    });
</script>
@endpush
@endif

@endsection
