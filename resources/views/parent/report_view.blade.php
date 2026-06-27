@extends('layouts.dashboard')

@section('title', 'Rapor ' . ucfirst($reportCard->academicYear?->semester ?? '') . ' ' . ($reportCard->academicYear?->year ?? '') . ' - e-Rapor')
@section('back_url', route('parent.report'))

@section('content')

@php
    $ay          = $reportCard->academicYear;
    $semLabel    = $ay ? ucfirst($ay->semester) . ' ' . $ay->year : '—';
    $classroom   = $student->classroom;
    $finalScore  = (float) ($reportCard->final_score ?? 0);

    $className = $classroom?->name ?? '';

    preg_match('/^(XII|XI|X)\b/i', $className, $matches);

    $tingkat = strtoupper($matches[1] ?? '');

    $fase = match ($tingkat) {
        'X'   => 'E',
        'XI'  => 'F',
        'XII' => 'F',
        default => '—',
    };

    // Memisahkan mata pelajaran Akademik vs Ekstrakurikuler berdasarkan kolom 'type' di tabel subjects
    $mapelAkademik = $grades->filter(fn($g) => $g->classroomSubjectTeacher?->subject?->type === 'academic');
    $mapelEkstra   = $grades->filter(fn($g) => $g->classroomSubjectTeacher?->subject?->type === 'extracurricular');
    $mapelLain     = $grades->filter(fn($g) => !in_array($g->classroomSubjectTeacher?->subject?->type, ['academic', 'extracurricular']));

    $hasCategory   = $mapelAkademik->isNotEmpty() || $mapelEkstra->isNotEmpty();
    $allGrades     = $hasCategory ? null : $grades;
@endphp

{{-- TOMBOL KEMBALI (Responsive & Mengikuti Desain Referensi) --}}
<div class="pt-2 mb-6 relative z-20 px-2 sm:px-4 md:px-0 print:hidden">
    <a href="{{ route('parent.report') }}"
       class="flex items-center w-full md:w-max md:inline-flex gap-4 bg-white rounded-2xl shadow-md border border-slate-100 p-3 md:pr-6 hover:bg-slate-50 transition-colors group">

        {{-- Icon Wrapper --}}
        <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 transition-colors group-hover:bg-slate-800 group-hover:text-white">
            <i class="fa-solid fa-arrow-left text-sm md:text-base"></i>
        </div>

        {{-- Text Label --}}
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none block mb-1">Navigasi</span>
            <span class="text-xs md:text-sm font-black text-slate-800 uppercase leading-tight block">Daftar Rapor</span>
        </div>
    </a>
</div>

{{-- AREA RAPOR CETAK --}}
<div class="max-w-[210mm] w-full mx-auto document-font text-gray-900 px-2 sm:px-4 md:px-0" id="rapor-print-area">

    {{-- ========================================== --}}
    {{-- HALAMAN 1: IDENTITAS & NILAI --}}
    {{-- ========================================== --}}
    <div class="bg-white p-3 sm:p-6 md:p-12 shadow-xl print:shadow-none print:p-0 mb-8 print:mb-0" id="rapor-page-1">

        {{-- KOP SEKOLAH & IDENTITAS ATAS --}}
        <div class="mb-6 grid grid-cols-2 gap-2 sm:gap-4 text-[9px] sm:text-xs md:text-sm tracking-tight sm:tracking-normal">
            <div>
                <table class="w-full table-fixed">
                    <tr>
                        <td class="py-0.5 font-bold w-14 sm:w-24 align-top">Nama Murid</td>
                        <td class="py-0.5 align-top">: {{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">NIS/NISN</td>
                        <td class="py-0.5 align-top">: {{ $student->nisn ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">Sekolah</td>
                        <td class="py-0.5 align-top">: SMK TI AIRLANGGA</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">Alamat</td>
                        <td class="py-0.5 align-top">: Jl. Pahlawan 2A Samarinda</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full table-fixed">
                    <tr>
                        <td class="py-0.5 font-bold w-14 sm:w-24 align-top">Kelas</td>
                        <td class="py-0.5 align-top">: {{ $className }}</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">Fase</td>
                        <td class="py-0.5 align-top">: {{ $fase }}</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">Semester</td>
                        <td class="py-0.5 align-top">: {{ $ay?->semester === 'ganjil' ? '1' : '2' }}</td>
                    </tr>
                    <tr>
                        <td class="py-0.5 font-bold align-top">Tahun Ajaran</td>
                        <td class="py-0.5 align-top">: {{ $ay?->year ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- JUDUL DOKUMEN --}}
        <div class="text-center mb-4">
            <h3 class="text-xs sm:text-base font-bold uppercase border-t-2 border-black pt-2 pb-2 mt-1">Laporan Hasil Belajar</h3>
        </div>

        {{-- TABEL NILAI --}}
        <div class="mb-4 w-full">
            @php $noCounter = 1; @endphp

            <table class="w-full text-[9px] sm:text-xs md:text-sm border-collapse border border-black mb-4 table-fixed sm:table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-black px-1 py-1.5 sm:py-3 text-center w-[6%]">No</th>
                        <th class="border border-black px-1.5 py-1.5 sm:px-4 sm:py-3 text-left w-[34%]">Mata Pelajaran</th>
                        <th class="border border-black px-1 py-1.5 text-center w-[12%]">Nilai Akhir</th>
                        <th class="border border-black px-1.5 py-1.5 sm:px-4 sm:py-3 text-left w-[48%]">Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- KELOMPOK A: AKADEMIK --}}
                    @if($hasCategory && $mapelAkademik->isNotEmpty())
                        <tr>
                            <td colspan="4" class="border border-black px-2 py-1 sm:py-2 font-bold bg-gray-50 uppercase text-[9px] sm:text-xs">
                                A. Akademik
                            </td>
                        </tr>
                        @foreach($mapelAkademik as $g)
                            @php
                                $subjectName = $g->classroomSubjectTeacher?->subject?->name ?? 'Mata Pelajaran';
                                $score       = (float) ($g->final_score ?? 0);
                                $capaian     = $g->description ?? '—';
                            @endphp
                            <tr>
                                <td class="border border-black px-1 py-1 text-center align-top">{{ $noCounter++ }}</td>
                                <td class="border border-black px-1.5 py-1 align-top break-words">{{ $subjectName }}</td>
                                <td class="border border-black px-1 py-1 text-center align-top font-bold">{{ $score > 0 ? round($score) : '—' }}</td>
                                <td class="border border-black px-1.5 py-1 text-justify align-top text-[8.5px] sm:text-xs tracking-tight sm:tracking-normal whitespace-normal">{{ $capaian }}</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- KELOMPOK B: EKSTRAKURIKULER --}}
                    @if($hasCategory && $mapelEkstra->isNotEmpty())
                        <tr>
                            <td colspan="4" class="border border-black px-2 py-1 sm:py-2 font-bold bg-gray-50 uppercase text-[9px] sm:text-xs">
                                B. Ekstrakurikuler
                            </td>
                        </tr>
                        @foreach($mapelEkstra as $g)
                            @php
                                $subjectName = $g->classroomSubjectTeacher?->subject?->name ?? 'Ekstrakurikuler';
                                $score       = (float) ($g->final_score ?? 0);
                                $capaian     = $g->description ?? '—';
                            @endphp
                            <tr>
                                <td class="border border-black px-1 py-1 text-center align-top">{{ $noCounter++ }}</td>
                                <td class="border border-black px-1.5 py-1 align-top break-words">{{ $subjectName }}</td>
                                <td class="border border-black px-1 py-1 text-center align-top font-bold">{{ $score > 0 ? round($score) : '—' }}</td>
                                <td class="border border-black px-1.5 py-1 text-justify align-top text-[8.5px] sm:text-xs tracking-tight sm:tracking-normal whitespace-normal">{{ $capaian }}</td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- TANPA KATEGORI (Fallback) --}}
                    @if(!$hasCategory && $allGrades && $allGrades->isNotEmpty())
                        @foreach($allGrades as $g)
                            @php
                                $subjectName = $g->classroomSubjectTeacher?->subject?->name ?? 'Mata Pelajaran';
                                $score       = (float) ($g->final_score ?? 0);
                                $capaian     = $g->description ?? '—';
                            @endphp
                            <tr>
                                <td class="border border-black px-1 py-1 text-center align-top">{{ $loop->iteration }}</td>
                                <td class="border border-black px-1.5 py-1 align-top break-words">{{ $subjectName }}</td>
                                <td class="border border-black px-1 py-1 text-center align-top font-bold">{{ $score > 0 ? round($score) : '—' }}</td>
                                <td class="border border-black px-1.5 py-1 text-justify align-top text-[8.5px] sm:text-xs tracking-tight sm:tracking-normal whitespace-normal">{{ $capaian }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if($grades->isEmpty())
                        <tr>
                            <td colspan="4" class="border border-black px-4 py-8 text-center text-gray-500 italic">
                                Data nilai belum tersedia untuk semester ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- HALAMAN 2: KEHADIRAN, CATATAN & TTD --}}
    {{-- ========================================== --}}
    <div class="bg-white p-3 sm:p-6 md:p-12 shadow-xl print:shadow-none print:p-0 page-break-before" id="rapor-page-2">

        {{-- Header Mini di Halaman 2 --}}
        <div class="hidden print:block border-b border-black pb-2 mb-4 text-xs font-semibold">
            <div class="flex justify-between items-center">
                <span>Nama: {{ $student->name }}</span>
                <span>Kelas: {{ $className }} | Sem: {{ $ay?->semester === 'ganjil' ? '1' : '2' }}</span>
            </div>
        </div>

        {{-- Grid Kehadiran & Catatan --}}
        <div class="grid grid-cols-2 gap-3 sm:gap-8 mb-6 mt-2 md:mt-0">

            {{-- KEHADIRAN --}}
            <div>
                <h4 class="font-bold text-[10px] sm:text-sm mb-1.5 uppercase">Ketidakhadiran</h4>
                <table class="w-full text-[9px] sm:text-xs md:text-sm border-collapse border border-black">
                    @php $att = $attendance; @endphp
                    <tbody>
                        <tr>
                            <td class="border border-black px-2 py-1.5 sm:px-4 w-1/2">Sakit</td>
                            <td class="border border-black px-1 py-1.5 text-center w-10 sm:w-16 font-bold">{{ $att ? $att->sakit : 0 }}</td>
                            <td class="border border-black px-1 py-1.5 text-center">hari</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-2 py-1.5">Izin</td>
                            <td class="border border-black px-1 py-1.5 text-center font-bold">{{ $att ? $att->izin : 0 }}</td>
                            <td class="border border-black px-1 py-1.5 text-center">hari</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-2 py-1.5">Tanpa Keterangan</td>
                            <td class="border border-black px-1 py-1.5 text-center font-bold">{{ $att ? $att->alpha : 0 }}</td>
                            <td class="border border-black px-1 py-1.5 text-center">hari</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- CATATAN WALI KELAS --}}
            <div>
                <h4 class="font-bold text-[10px] sm:text-sm mb-1.5 uppercase">Catatan Wali Kelas</h4>
                <div class="border border-black p-2 sm:p-4 min-h-[69px] sm:min-h-[100px] text-[8.5px] sm:text-xs md:text-sm text-justify leading-normal">
                    @if($reportCard->description)
                        "{!! nl2br(e($reportCard->description)) !!}"
                    @else
                        <span class="text-gray-400 italic">Tidak ada catatan.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- KETERANGAN NAIK KELAS / LULUS --}}
        @if($ay?->semester === 'genap')
        <div class="mb-6">
            <h4 class="font-bold text-[10px] sm:text-sm mb-1.5 uppercase">Keterangan Akhir Tahun</h4>
            <div class="border border-black p-2 sm:p-4 text-[9px] sm:text-sm font-bold leading-normal">
                Berdasarkan pencapaian seluruh kompetensi, peserta didik dinyatakan: <br><br>
                <span class="text-[10px] sm:text-base uppercase underline">Naik ke kelas / Lulus (Coret yang tidak perlu)</span>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- TOMBOL DOWNLOAD PDF --}}
<div class="max-w-[210mm] mx-auto mt-8 mb-16 px-2 sm:px-4 md:px-0 print:hidden">
    <div class="bg-slate-800 rounded-xl p-4 md:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
        <div class="text-center sm:text-left">
            <h3 class="text-white font-bold text-base sm:text-lg mb-1">Unduh e-Rapor Format Resmi</h3>
            <p class="text-slate-300 text-xs sm:text-sm">Gunakan tombol cetak untuk menyimpan sebagai PDF dokumen resmi.</p>
        </div>
        <button type="button"
                onclick="window.print()"
                class="shrink-0 bg-white hover:bg-slate-100 text-slate-800 font-bold text-xs sm:text-sm px-6 py-2.5 rounded-lg transition-all shadow flex items-center gap-2 w-full sm:w-auto justify-center">
            <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>
</div>

<style>
    .document-font {
        font-family: 'Times New Roman', Times, serif;
    }

    @media print {
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            background-color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body * { visibility: hidden; }
        #rapor-print-area, #rapor-print-area * { visibility: visible; }
        #rapor-print-area {
            position: absolute;
            top: 0; left: 0; width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }
        .page-break-before {
            page-break-before: always;
            break-before: page;
        }
        .print\:shadow-none { box-shadow: none !important; }
        .print\:p-0 { padding: 0 !important; }
        .print\:hidden { display: none !important; }

        /* Kembalikan ukuran font standar dokumen A4 asli saat di-print */
        .mb-6.grid-cols-2 { gap: 1rem !important; font-size: 0.875rem !important; }
        table { font-size: 0.875rem !important; table-layout: auto !important; }
        th, td { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        td.text-justify { font-size: 0.75rem !important; }
        .grid-cols-2 { gap: 2rem !important; }
        h4 { font-size: 0.875rem !important; }
        .min-h-\[69px\] { min-height: 100px !important; font-size: 0.875rem !important; }
        .mt-6 { margin-top: 3rem !important; font-size: 0.875rem !important; }
        .mb-10 { margin-bottom: 4rem !important; }
        .h-4 { height: 1.25rem !important; }
        .text-\[8px\] { font-size: 0.75rem !important; }
    }
</style>
@endsection
