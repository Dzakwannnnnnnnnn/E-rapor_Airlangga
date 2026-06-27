@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - e-Rapor')

@section('content')

<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
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
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Dashboard Admin</h3>
                    <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-house-laptop text-base text-secondary opacity-95"></i>
                            <span>Ringkasan Kondisi Akademik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-7xl mx-auto mt-6 space-y-8 px-4 md:px-0 pb-16">

    <div class="bg-[#f8faff] rounded-2xl shadow-sm border border-blue-100 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-8 -mt-20 relative z-20">
        <div class="flex-1">
            <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-widest mb-3">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Tahun Akademik Aktif</span>
            </div>
            @if($activeYear)
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">{{ $activeYear->year }}</h2>
                <h3 class="text-xl md:text-2xl font-bold text-slate-600 mt-1">Semester {{ ucfirst($activeYear->semester) }}</h3>

                <div class="flex items-center gap-3 mt-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> AKTIF
                    </span>
                    <span class="text-xs text-slate-500">Periode ini sedang berjalan dan digunakan dalam proses penilaian.</span>
                </div>
            @else
                <h2 class="text-3xl font-black text-danger tracking-tight mt-2">Belum Diatur</h2>
                <p class="text-sm text-slate-500 mt-2">Silakan aktifkan tahun ajaran di menu pengaturan.</p>
            @endif
        </div>
    </div>

    <div class="space-y-4 pt-4">


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <h4 class="font-bold text-slate-700 text-sm mb-4">Komposisi Pengguna</h4>
                <div class="relative w-full h-64">
                    <canvas id="chartUserComposition"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <h4 class="font-bold text-slate-700 text-sm mb-4">Distribusi Siswa per Kelas</h4>
                <div class="relative w-full h-64">
                    <canvas id="chartStudentsPerClass"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <h4 class="font-bold text-slate-700 text-sm mb-4">Mata Pelajaran (Akademik & Ekstra)</h4>
                <div class="relative w-full h-64">
                    <canvas id="pieChartSubjects"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
                <h4 class="font-bold text-slate-700 text-sm mb-4">Sebaran Guru Berdasarkan Gender</h4>
                <div class="relative w-full h-64">
                    <canvas id="horizontalBarChartTeachers"></canvas>
                </div>
            </div>
        </div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <a href="{{ Route::has('admin.students.index') ? route('admin.students.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <i class="fa-solid fa-user-group text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Siswa</span>
                <span class="text-xs text-slate-500 mt-0.5">Peserta didik terdaftar</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-xl font-black text-sm transition-colors group-hover:bg-blue-100">{{ $studentCount }}</span>
            <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </div>
    </a>

    <a href="{{ Route::has('admin.teachers.index') ? route('admin.teachers.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-emerald-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <i class="fa-solid fa-chalkboard-user text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Guru</span>
                <span class="text-xs text-slate-500 mt-0.5">Guru pengajar terdaftar</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-xl font-black text-sm transition-colors group-hover:bg-emerald-100">{{ $teacherCount }}</span>
            <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </div>
    </a>

    <a href="{{ Route::has('admin.classrooms.index') ? route('admin.classrooms.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-purple-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <i class="fa-solid fa-school text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Kelas</span>
                <span class="text-xs text-slate-500 mt-0.5">Kelas aktif tersedia</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-purple-50 text-purple-600 px-2.5 py-1 rounded-xl font-black text-sm transition-colors group-hover:bg-purple-100">{{ $classroomCount }}</span>
            <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </div>
    </a>

    <a href="{{ Route::has('admin.subjects.index') ? route('admin.subjects.index') : '#' }}" class="group bg-white rounded-2xl border border-slate-100 p-5 flex items-center justify-between shadow-sm hover:shadow-md hover:border-slate-200 transition-all border-l-4 border-l-orange-500">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <i class="fa-solid fa-book-open text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-slate-800 text-base group-hover:text-primary transition-colors">Mata Pelajaran</span>
                <span class="text-xs text-slate-500 mt-0.5">Akademik & Ekstrakurikuler</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-orange-50 text-orange-600 px-2.5 py-1 rounded-xl font-black text-sm transition-colors group-hover:bg-orange-100">{{ $subjectCount }}</span>
            <div class="text-slate-300 group-hover:text-primary transition-colors mr-1">
                <i class="fa-solid fa-chevron-right transition-transform group-hover:translate-x-1"></i>
            </div>
        </div>
    </a>
</div>


</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11, family: "'Inter', sans-serif" } } }
        }
    };

    // 1. Chart Pengguna
    const ctxUsers = document.getElementById('chartUserComposition').getContext('2d');
    new Chart(ctxUsers, {
        type: 'doughnut',
        data: {
            labels: @json($chartUserComposition['labels'] ?? []),
            datasets: [{
                data: @json($chartUserComposition['data'] ?? []),
                backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: commonOptions
    });

    // 2. Chart Siswa per Kelas
    const ctxClasses = document.getElementById('chartStudentsPerClass').getContext('2d');
    new Chart(ctxClasses, {
        type: 'bar',
        data: {
            labels: @json($chartStudentsPerClass['labels'] ?? []),
            datasets: [{
                label: 'Jumlah Siswa',
                data: @json($chartStudentsPerClass['data'] ?? []),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 5 } } }
        }
    });

    // 3. Chart Mapel (Akademik & Ekstra)
    const ctxSubjects = document.getElementById('pieChartSubjects').getContext('2d');
    new Chart(ctxSubjects, {
        type: 'pie',
        data: {
            labels: @json($chartSubjectsPerType['labels'] ?? []),
            datasets: [{
                data: @json($chartSubjectsPerType['data'] ?? []),
                backgroundColor: ['rgba(14, 165, 233, 0.8)', 'rgba(168, 85, 247, 0.8)'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: commonOptions
    });

    // 4. Chart Guru per Gender
    const ctxTeachers = document.getElementById('horizontalBarChartTeachers').getContext('2d');
    new Chart(ctxTeachers, {
        type: 'bar',
        data: {
            labels: @json($chartTeachersPerGender['labels'] ?? []),
            datasets: [{
                label: 'Jumlah Guru',
                data: @json($chartTeachersPerGender['data'] ?? []),
                backgroundColor: ['rgba(59, 130, 246, 0.7)', 'rgba(236, 72, 153, 0.7)'],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y', // Membuat bar menjadi horizontal
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
