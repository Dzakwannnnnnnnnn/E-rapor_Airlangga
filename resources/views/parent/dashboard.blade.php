@extends('layouts.dashboard ')

@section('title', 'Parent Dashboard - e-Rapor')

@section('content')
@php
    $parentName = Auth::user()->name;
    $parent = Auth::user()->parent;

    // Fetch student details from DB dynamically using the parent relationship
    $student = $parent ? \App\Models\Student::where('parent_id', $parent->id)->first() : null;
    $studentName = $student ? $student->name : '';

    $grades = $student ? $student->grades()->with('classroomSubjectTeacher.subject', 'classroomSubjectTeacher.teacher.user')->get() : collect();
    $attendance = $student ? $student->attendances()->first() : null;
    $reportCard = $student ? $student->reportCards()->first() : null;
@endphp

<div class="min-h-screen bg-[#F4F6F9] flex flex-col font-sans selection:bg-[#003399]/25 selection:text-[#003399]">

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-none uppercase">Laporan Akademik Siswa</h2>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-1.5">
                Wali Murid dari: <span class="text-[#003399]">{{ $studentName }}</span> (Kelas {{ $student->classroom->name ?? 'N/A' }} - {{ $student->classroom->major ?? '' }})
            </p>
        </div>

        @if($student)
            @if($reportCard && $reportCard->is_validated)
                <!-- Student Header & Highlights -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Rapor Card Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-all">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Rata-Rata Nilai</p>
                            <p class="text-3xl font-black text-[#003399] tracking-tight mt-2">{{ $reportCard ? number_format($reportCard->final_score, 2) : 'N/A' }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[10px] text-emerald-600 font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-arrow-trend-up"></i> Performa Baik
                        </div>
                    </div>

                    <!-- Rank Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-all">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Peringkat Kelas</p>
                            <p class="text-3xl font-black text-[#FFB800] tracking-tight mt-2">#{{ $reportCard->rank ?? 'N/A' }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            Dari {{ \App\Models\Student::where('classroom_id', $student->classroom_id)->count() }} Siswa
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-all col-span-1 md:col-span-2">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-3">Kehadiran Semester</p>
                            <div class="grid grid-cols-4 gap-4 text-center">
                                <div class="bg-emerald-50 rounded-lg p-2">
                                    <span class="block text-xs font-black text-emerald-700">{{ $attendance->hadir ?? 0 }}</span>
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-emerald-600">Hadir</span>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-2">
                                    <span class="block text-xs font-black text-blue-700">{{ $attendance->sakit ?? 0 }}</span>
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-blue-600">Sakit</span>
                                </div>
                                <div class="bg-amber-50 rounded-lg p-2">
                                    <span class="block text-xs font-black text-amber-700">{{ $attendance->izin ?? 0 }}</span>
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-amber-600">Izin</span>
                                </div>
                                <div class="bg-red-50 rounded-lg p-2">
                                    <span class="block text-xs font-black text-red-700">{{ $attendance->alpha ?? 0 }}</span>
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-red-600">Alpha</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grades Table & Notes -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Grades Details -->
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Detail Nilai Mata Pelajaran</h3>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Smt Ganjil 2025/2026</span>
                        </div>

                        <div class="flex flex-col gap-4">
                            @forelse($grades as $grade)
                                <div class="p-4 rounded-xl border border-slate-100 hover:border-slate-200 transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-black text-slate-800">{{ $grade->classroomSubjectTeacher->subject->name ?? 'N/A' }}</h4>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                                Guru: {{ $grade->classroomSubjectTeacher->teacher->user->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-black text-[#003399]">{{ number_format($grade->final_score, 2) }}</span>
                                            <span class="block text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Predikat: {{ $grade->final_score >= 85 ? 'A' : ($grade->final_score >= 75 ? 'B' : 'C') }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 bg-slate-50 rounded-lg p-2.5 mt-2 italic">
                                        "{{ $grade->description }}"
                                    </p>
                                </div>
                            @empty
                                <p class="text-xs font-bold text-slate-400 py-6 text-center">Belum ada data nilai.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Notes & Download Card -->
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- School Message -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">Catatan Wali Kelas</h3>
                            <div class="bg-[#003399]/5 border-l-4 border-[#003399] p-4 rounded-r-xl">
                                <p class="text-xs text-slate-700 italic">
                                    "{{ $reportCard->description ?? 'Belum ada catatan wali kelas.' }}"
                                </p>
                            </div>
                        </div>

                        <!-- Print Report PDF -->
                        <div class="bg-[#003399] text-white rounded-2xl border border-[#FFB800]/20 shadow-lg p-6 relative overflow-hidden">
                            <div class="absolute -right-6 -bottom-6 opacity-10 text-white text-8xl pointer-events-none">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <h3 class="text-sm font-black tracking-tight uppercase leading-none mb-1">Cetak e-Rapor Resmi</h3>
                            <p class="text-[9px] text-[#FFB800] font-bold uppercase tracking-widest mb-4">Format PDF Siap Cetak</p>
                            <button class="w-full bg-white hover:bg-slate-50 text-[#003399] font-black text-xs uppercase tracking-widest py-3.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-download"></i> Unduh Rapor PDF
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Elegant Notice if report card is not validated -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 md:p-12 text-center max-w-2xl mx-auto my-8 relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#FFB800]/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-[#003399]/5 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-6 shadow-sm border border-amber-100">
                            <i class="fa-solid fa-hourglass-half text-3xl animate-pulse"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight leading-none mb-3">Rapor Belum Disahkan</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                            Hasil laporan akademik untuk <span class="text-[#003399] font-bold">{{ $studentName }}</span> saat ini sedang dalam proses penyusunan oleh Wali Kelas dan belum disahkan oleh Kepala Sekolah/Admin.
                        </p>
                        <div class="mt-8 px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl inline-flex items-center gap-2.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-circle-info text-amber-500 text-xs"></i>
                            Silakan periksa kembali secara berkala.
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-3xl mb-3"></i>
                <h3 class="text-md font-black text-slate-800">Siswa Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1">Gagal memuat profil siswa untuk wali murid ini.</p>
            </div>
        @endif
    </main>
</div>
@endsection
