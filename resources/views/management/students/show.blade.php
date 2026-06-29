@extends('layouts.dashboard')

@section('title', 'Detail Profil Siswa - ' . $student->name . ' - e-Rapor')

@section('back_url', route('admin.students.index'))

@section('content')
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">

        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20 uppercase">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-md border border-white">
                    <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-circle-check text-[9px] text-emerald-600"></i> Siswa Aktif
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">{{ $student->name }}</h3>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chalkboard-user text-base text-secondary opacity-95"></i>
                        <span>Kelas: <strong class="text-white">{{ $student->classroom->name ?? 'Belum Diatur' }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar-day text-base text-secondary opacity-95"></i>
                        <span>Terdaftar: <strong class="text-white">{{ $student->created_at ? $student->created_at->format('d M Y') : '-' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 space-y-6">

    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0">
        <a href="{{ route('admin.students.index') }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Daftar Siswa</span>
            </div>
        </a>
    </div>

    <div class="space-y-3 px-4 sm:px-0">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Informasi Administrasi Siswa</h4>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-1.5">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider pl-0.5">Nama Lengkap Siswa</span>
                    <div class="flex items-center gap-3 bg-slate-50/70 border border-slate-100 rounded-xl px-4 py-3">
                        <i class="fa-solid fa-user text-slate-400 text-sm w-4 text-center"></i>
                        <span class="text-sm font-semibold text-slate-700">{{ $student->name }}</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider pl-0.5">Nomor Induk Siswa Nasional (NISN)</span>
                    <div class="flex items-center gap-3 bg-slate-50/70 border border-slate-100 rounded-xl px-4 py-3">
                        <i class="fa-solid fa-id-card text-slate-400 text-sm w-4 text-center"></i>
                        <span class="text-sm font-semibold text-slate-700 tracking-wide font-mono">{{ $student->nisn }}</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider pl-0.5">Rombongan Belajar / Kelas</span>
                    <div class="flex items-center gap-3 bg-slate-50/70 border border-slate-100 rounded-xl px-4 py-3">
                        <i class="fa-solid fa-school text-slate-400 text-sm w-4 text-center"></i>
                        <span class="text-sm font-semibold text-slate-700 uppercase">{{ $student->classroom->name ?? 'Belum Diatur' }}</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider pl-0.5">Waktu Registrasi Sistem</span>
                    <div class="flex items-center gap-3 bg-slate-50/70 border border-slate-100 rounded-xl px-4 py-3">
                        <i class="fa-regular fa-clock text-slate-400 text-sm w-4 text-center"></i>
                        <span class="text-sm font-semibold text-slate-700">
                            {{ $student->created_at ? $student->created_at->translatedFormat('d F Y (H:i)') : '-' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-1.5 md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider pl-0.5">Orang Tua / Wali (Maksimal 2 Hubungan Berbeda)</span>
                    <div class="bg-slate-50/70 border border-slate-100 rounded-xl px-4 py-3 space-y-2.5">
                        @if($student->parents && $student->parents->count() > 0)
                            @foreach($student->parents as $p)
                                @if($p->user)
                                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0 last:pb-0 first:pt-0">
                                        <div class="flex items-center gap-3">
                                            <i class="fa-solid fa-user text-slate-400 text-sm w-4 text-center"></i>
                                            <span class="text-sm font-semibold text-slate-700">
                                                {{ $p->user->name }} (<span class="capitalize text-primary font-bold">{{ $p->relation }}</span>)
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.parents.show', $p->user->id) }}" class="text-xs font-bold text-primary hover:underline">
                                            Detail Wali Murid
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="flex items-center gap-3 py-1">
                                <i class="fa-solid fa-user-group text-slate-400 text-sm w-4 text-center"></i>
                                <span class="text-sm font-semibold text-slate-400">Belum dihubungkan ke akun Orang Tua / Wali</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="bg-blue-50 border-l-4 border-primary text-slate-900 rounded-2xl p-4 shadow-sm flex items-center gap-4 select-none mx-4 sm:mx-0">
        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-info text-lg"></i>
        </div>
        <div class="flex-1">
            <h5 class="font-extrabold text-xs uppercase tracking-wide text-primary">Informasi Manajemen Nilai</h5>
            <p class="text-xs text-slate-600 mt-0.5 leading-normal">Data akademis dan pelaporan hasil belajar siswa terikat secara otomatis dengan rombongan belajar yang aktif. Pastikan pemetaan kelas dan penugasan wali murid telah diverifikasi demi menjaga validitas data e-Rapor.</p>
        </div>
    </div>

    <div class="hidden md:flex items-center justify-end gap-4 pt-2">
        <a href="{{ route('admin.students.index') }}"
           class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-50 transition-all text-center">
            Kembali
        </a>
        <a href="{{ route('admin.students.edit', $student->id) }}"
           class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 text-center">
            <i class="fa-solid fa-user-pen mr-1.5"></i> Edit Profil Siswa
        </a>
    </div>

</div>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 pb-safe">
    <div class="flex items-center gap-3 max-w-lg mx-auto">
        <a href="{{ route('admin.students.index') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
            Kembali
        </a>
        <a href="{{ route('admin.students.edit', $student->id) }}"
           class="flex-[2] flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-br from-primary to-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all border-b-2 border-blue-900">
            <i class="fa-solid fa-user-pen text-xs"></i>
            Edit Profil Siswa
        </a>
    </div>
</div>
@endsection
