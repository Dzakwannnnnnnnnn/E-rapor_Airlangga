@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - e-Rapor')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] flex flex-col font-sans selection:bg-[#003399]/25 selection:text-[#003399]">

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-none uppercase">Selamat Datang, <span class="text-[#003399]">{{ Auth::user()->name }}</span>!</h2>
            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mt-1.5">Panel Kontrol Administrator — Tahun Ajaran 2025/2026 (Semester Ganjil)</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Teachers -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#003399] flex items-center justify-center">
                        <i class="fa-solid fa-chalkboard-user text-lg"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Aktif</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none">2</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Total Guru</p>
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900 text-6xl pointer-events-none">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>

            <!-- Card 2: Students -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-[#FFB800] flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Aktif</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none">2</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Total Siswa</p>
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900 text-6xl pointer-events-none">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
            </div>

            <!-- Card 3: Classrooms -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-school text-lg"></i>
                    </div>
                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Aktif</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none">2</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Total Kelas</p>
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900 text-6xl pointer-events-none">
                    <i class="fa-solid fa-school"></i>
                </div>
            </div>

            <!-- Card 4: Subjects -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 relative overflow-hidden transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                        <i class="fa-solid fa-book-open text-lg"></i>
                    </div>
                    <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full uppercase tracking-wider">Kurikulum</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none">4</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Mata Pelajaran</p>
                <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900 text-6xl pointer-events-none">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Actions -->
            <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col gap-4">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Menu Cepat Administrator</h3>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#003399] flex items-center justify-center">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                    </div>
                    <span>Tambah Data Guru</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-[#FFB800] flex items-center justify-center">
                        <i class="fa-solid fa-user-graduate text-xs"></i>
                    </div>
                    <span>Tambah Data Siswa</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-xs"></i>
                    </div>
                    <span>Konfigurasi Kelas</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-[#003399] hover:bg-slate-50 transition-all font-bold text-slate-700 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice text-xs"></i>
                    </div>
                    <span>Unduh Laporan Umum</span>
                </a>
            </div>

            <!-- List of Users/System Log -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Aktivitas Pengguna Uji Coba</h3>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Terakhir diperbarui hari ini</span>
                </div>

                <div class="flex flex-col gap-4">
                    <!-- User Row 1 -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#003399]/10 text-[#003399] flex items-center justify-center font-bold text-xs">GP</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Guru Penguji PPLG</h4>
                                <p class="text-[10px] text-slate-500">guru@test.com</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-blue-50 text-[#003399] text-[9px] font-bold uppercase tracking-wider rounded-md">Guru</span>
                    </div>

                    <!-- User Row 2 -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#FFB800]/10 text-[#FFB800] flex items-center justify-center font-bold text-xs">WM</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Wali Murid Ilham</h4>
                                <p class="text-[10px] text-slate-500">wali@test.com</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-amber-50 text-[#FFB800] text-[9px] font-bold uppercase tracking-wider rounded-md">Wali</span>
                    </div>

                    <!-- User Row 3 -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs">IS</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Ilham</h4>
                                <p class="text-[10px] text-slate-500">Siswa (NISN: 0071234561)</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold uppercase tracking-wider rounded-md">Siswa</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
