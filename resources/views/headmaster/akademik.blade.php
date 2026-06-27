@extends('layouts.dashboard')
@section('title', 'Laporan Nilai Sekolah · e-Rapor')
@section('content')

{{-- Bagian Atas / Hero --}}
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    <div class="bg-primary text-white pt-10 pb-24 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-3xl shadow-md border-4 border-white/20 shrink-0">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                    <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase tracking-wide">Laporan Nilai Sekolah</h3>
                    <p class="text-white/70 text-xs md:text-sm mt-1.5">Melihat rata-rata nilai sekolah, siswa yang lulus KKM, dan perbandingan antar kelas</p>
                </div>
            </div>

            {{-- Pilihan Semester --}}
            <div class="shrink-0 self-center md:self-start">
                <form method="GET" action="{{ route('headmaster.akademik') }}">
                    <div class="relative">
                        <select name="academic_year_id" onchange="this.form.submit()"
                            class="appearance-none bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl pl-4 pr-10 py-2.5 text-xs font-bold text-white focus:outline-none cursor-pointer min-w-[180px]">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" class="text-slate-800 bg-white"
                                    {{ $selectedYear && $selectedYear->id == $ay->id ? 'selected' : '' }}>
                                    {{ ucfirst($ay->semester) }} {{ $ay->year }} {{ $ay->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-white">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-6xl mx-auto -mt-12 space-y-6 px-4 md:px-0 pb-16 relative z-20">

    {{-- Kotak Informasi Utama (School-wide Stats) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Rata-rata Sekolah --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Rata-Rata Nilai Sekolah</span>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $schoolAvg }}</h3>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Gabungan semua mata pelajaran</p>
            </div>
        </div>

        {{-- Persentase Lulus KKM --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="w-full">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Siswa Lulus KKM (Nilai &ge; 75)</span>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $schoolAboveKkmPct }}%</h3>
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-1.5 overflow-hidden">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $schoolAboveKkmPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Pelajaran Paling Sulit --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pelajaran Nilai Terendah</span>
                <h3 class="text-sm font-black text-slate-800 mt-1 truncate" title="{{ $subjectStats->isNotEmpty() ? $subjectStats->keys()->first() : '—' }}">
                    {{ $subjectStats->isNotEmpty() ? $subjectStats->keys()->first() : '—' }}
                </h3>
                <p class="text-[11px] text-rose-600 font-extrabold mt-0.5">
                    Rata-rata: {{ $subjectStats->isNotEmpty() ? $subjectStats->first()['avg'] : '—' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Grafik dan Daftar Nilai Pelajaran --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Grafik Nilai Kelas --}}
        <div class="md:col-span-2 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <h4 class="text-xs font-black text-slate-700 uppercase tracking-wide mb-4"><i class="fa-solid fa-chart-bar mr-1.5 text-blue-500"></i>Grafik Perbandingan Nilai Antar Kelas</h4>
            <div class="h-64 relative">
                <canvas id="classroomChart"></canvas>
            </div>
        </div>

        {{-- Urutan Nilai Pelajaran Sekolah --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <h4 class="text-xs font-black text-slate-700 uppercase tracking-wide mb-3"><i class="fa-solid fa-list-ol mr-1.5 text-amber-500"></i>Urutan Nilai Pelajaran (Dari Terendah)</h4>
            <div class="flex-1 overflow-y-auto pr-1 space-y-2.5 max-h-64">
                @forelse($subjectStats as $name => $data)
                <div class="flex items-center justify-between p-2 rounded-xl border border-slate-50 bg-slate-50/50">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-700 truncate" title="{{ $name }}">{{ $name }}</p>
                        <p class="text-[9px] text-slate-400 font-medium">{{ $data['total'] }} nilai masuk</p>
                    </div>
                    <span class="text-xs font-black px-2.5 py-1 rounded-lg {{ $data['avg'] >= 80 ? 'bg-emerald-50 text-emerald-600' : ($data['avg'] >= 75 ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600') }}">
                        {{ $data['avg'] }}
                    </span>
                </div>
                @empty
                <p class="text-center text-xs text-slate-400 my-auto">Belum ada data pelajaran.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tabel Utama Per Kelas --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary/10 text-primary rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-ranking-star text-sm"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-sm uppercase tracking-wide">Detail Nilai Per Kelas</h4>
                    <p class="text-[10px] text-slate-400 font-semibold">Diurutkan dari kelas dengan nilai rata-rata tertinggi</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-8">#</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Kelas</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-24">Rata-Rata</th>
                        <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest w-32">Banding Sekolah</th>
                        <th class="text-left px-4 py-3 text-[10px] font-black text-rose-500 uppercase tracking-widest w-36">Belum Lulus KKM</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Pelajaran Terbaik</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-rose-600 uppercase tracking-widest">Pelajaran Tersulit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($classroomStats->values() as $index => $stat)
                    @php
                        $avg = $stat['avg'];
                        $gap = $stat['gap'];
                        $avgColor = $avg === null ? 'text-slate-400' : ($avg >= 85 ? 'text-emerald-600' : ($avg >= 75 ? 'text-blue-600' : 'text-amber-600'));
                    @endphp
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/30' }} hover:bg-blue-50/20 transition-colors">
                        <td class="px-5 py-4 text-[11px] font-black text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-4">
                            <span class="font-black text-slate-800 text-xs block">{{ $stat['classroom']->name }}</span>
                            <span class="text-[9px] text-slate-400 font-bold">{{ $stat['classroom']->students->count() }} Siswa</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($avg !== null)
                                <span class="font-black text-base {{ $avgColor }}">{{ $avg }}</span>
                            @else
                                <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($gap !== null)
                                @if($gap >= 0)
                                    <span class="text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-1 rounded-md">
                                        +{{ $gap }} di atas rata-rata
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 px-2 py-1 rounded-md">
                                        {{ $gap }} di bawah rata-rata
                                    </span>
                                @endif
                            @else
                                <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($avg !== null)
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-slate-700 min-w-[32px] text-right">{{ $stat['underKkmPct'] }}%</span>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $stat['underKkmPct'] }}%"></div>
                                </div>
                            </div>
                            @else
                            <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        {{-- Pelajaran Nilai Tertinggi --}}
                        <td class="px-5 py-4">
                            @if($stat['highest'])
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[140px]" title="{{ $stat['highest']['name'] }}">
                                        {{ $stat['highest']['name'] }}
                                    </span>
                                    <span class="text-[10px] text-emerald-600 font-black">
                                        Nilai: {{ $stat['highest']['score'] }}
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        {{-- Pelajaran Nilai Terendah --}}
                        <td class="px-5 py-4">
                            @if($stat['lowest'])
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[140px]" title="{{ $stat['lowest']['name'] }}">
                                        {{ $stat['lowest']['name'] }}
                                    </span>
                                    <span class="text-[10px] text-rose-600 font-black">
                                        Nilai: {{ $stat['lowest']['score'] }}
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-400 text-xs font-bold">
                            Belum ada data nilai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('classroomChart').getContext('2d');
        const rawData = @json($classroomStats->values());

        const labels = rawData.map(item => item.classroom.name);
        const dataScores = rawData.map(item => item.avg || 0);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-Rata Kelas',
                    data: dataScores,
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { min: 50, max: 100 },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
