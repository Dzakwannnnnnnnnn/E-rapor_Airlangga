@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard - e-Rapor')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] flex flex-col font-sans selection:bg-[#003399]/25 selection:text-[#003399]">

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-none uppercase">Selamat Datang, <span class="text-[#003399]">{{ Auth::user()->name }}</span>!</h2>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-1.5">Panel Guru — Tahun Ajaran 2025/2026 (Semester Ganjil)</p>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#003399] flex items-center justify-center">
                    <i class="fa-solid fa-id-card text-xs"></i>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider leading-none">NIP Guru</p>
                    <p class="text-xs font-black text-slate-700 mt-0.5">{{ Auth::user()->teacher?->nip ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kelas Diajar</h4>
                <p class="text-2xl font-black text-slate-900 tracking-tight mt-1.5">XI PPLG</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">2 Siswa Terdaftar</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Mata Pelajaran Diampu</h4>
                <p class="text-2xl font-black text-slate-900 tracking-tight mt-1.5">2 Mapel</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Dasar Dasar PPLG, Matematika</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nilai Terinput</h4>
                <p class="text-2xl font-black text-slate-900 tracking-tight mt-1.5">100%</p>
                <p class="text-[10px] text-emerald-600 font-black uppercase tracking-wider mt-1"><i class="fa-solid fa-circle-check"></i> Selesai Dinilai</p>
            </div>
        </div>

        <!-- Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Subjects & Classroom List -->
            <div class="lg:col-span-2 flex flex-col gap-6">
                <!-- Students Grade Management Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Kelola Nilai Kelas XI PPLG</h3>
                        <button class="bg-[#003399] hover:bg-[#002266] text-white text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Nilai
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100 font-black">
                                    <th class="py-2.5">Siswa</th>
                                    <th class="py-2.5">Dasar PPLG</th>
                                    <th class="py-2.5">Matematika</th>
                                    <th class="py-2.5 text-center">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors font-bold text-slate-800">
                                    <td class="py-3.5 flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-50 text-[#003399] flex items-center justify-center font-bold text-[10px]">IL</div>
                                        <span>Ilham</span>
                                    </td>
                                    <td class="py-3.5 text-emerald-600 font-black">88.50</td>
                                    <td class="py-3.5 text-emerald-600 font-black">85.00</td>
                                    <td class="py-3.5 text-center text-slate-500">20 / 21 Hari</td>
                                </tr>
                                <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors font-bold text-slate-800">
                                    <td class="py-3.5 flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-[10px]">HE</div>
                                        <span>Herlambang</span>
                                    </td>
                                    <td class="py-3.5 text-slate-700 font-black">78.00</td>
                                    <td class="py-3.5 text-slate-700 font-black">80.00</td>
                                    <td class="py-3.5 text-center text-slate-500">18 / 21 Hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Actions Sidebar -->
            <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col gap-4">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Menu & Pintasan Guru</h3>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#003399] flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-xs"></i>
                    </div>
                    <span>Rekap Presensi Siswa</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                    </div>
                    <span>Statistik Nilai Kelas</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                        <i class="fa-solid fa-print text-xs"></i>
                    </div>
                    <span>Cetak Lembar Nilai</span>
                </a>
            </div>
        </div>
    </main>
</div>
@endsection
