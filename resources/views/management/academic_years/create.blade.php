@extends('layouts.dashboard')

@section('title', 'Tambah Tahun Ajaran Baru - e-Rapor')

@section('back_url', route('admin.academic_years.index'))

@section('content')
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++) <div class="w-1.5 h-1.5 bg-white rounded-full"></div> @endfor
        </div>
        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-calendar-plus"></i>
                </div>
            </div>
            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Tambah Tahun Ajaran</h3>
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2"><i class="fa-solid fa-school-flag text-base text-secondary opacity-95"></i> <span>Panel Manajemen Akademik</span></div>
                    <div class="flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-base text-secondary opacity-95"></i> <span>Pengaturan Periode Aktif</span></div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 space-y-6">
    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0">
        <a href="{{ route('admin.academic_years.index') }}" class="inline-flex items-center gap-4 bg-white rounded-2xl shadow-md border border-slate-100 p-4 hover:bg-slate-50 transition-colors group">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="pr-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none mt-1 block">Kembali ke Daftar Tahun Ajaran</span>
            </div>
        </a>
    </div>

    <form id="create-academic-form" method="POST" action="{{ route('admin.academic_years.store') }}" class="space-y-6 px-4 sm:px-0">
        @csrf
        <div class="space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Formulir Data Tahun Ajaran</h4>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="year" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Tahun Ajaran</label>
                        <input type="text" name="year" id="year" value="{{ old('year') }}" required class="w-full px-4 py-3.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-medium text-slate-800 shadow-sm transition-all" placeholder="Contoh: 2023/2024">
                    </div>
                    <div class="space-y-2">
                        <label for="semester" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Semester</label>
                        <select name="semester" id="semester" required class="w-full px-4 py-3.5 bg-slate-50/50 border border-slate-200 focus:border-primary rounded-xl text-sm font-medium text-slate-800 shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Semester</option>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label for="is_active" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Status Periode</label>
                        <select name="is_active" id="is_active" required class="w-full px-4 py-3.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-primary shadow-sm cursor-pointer">
                            <option value="0">Tidak Aktif</option>
                            <option value="1">Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-amber-50 border-l-4 border-amber-500 text-slate-900 rounded-2xl p-4 shadow-sm flex items-center gap-4 select-none">
            <div class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-circle-info text-lg"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-extrabold text-xs uppercase tracking-wide text-amber-700">Informasi Status Aktif</h5>
                <p class="text-xs text-slate-600 mt-0.5 leading-normal">Pastikan hanya satu tahun ajaran yang aktif untuk menghindari konflik sistem.</p>
            </div>
        </div>

        <div class="hidden md:flex items-center justify-end gap-4 pt-2 pb-6">
            <a href="{{ route('admin.academic_years.index') }}" class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-50 transition-all text-center">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                Simpan Tahun Ajaran
            </button>
        </div>

        <div class="h-24 md:h-0"></div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('create-academic-form');
        const isActiveSelect = document.getElementById('is_active');
        const activeYear = @json($activeYear);

        form.addEventListener('submit', function(e) {
            if (isActiveSelect.value === '1' && activeYear) {
                if (form.getAttribute('data-confirm-intercepted')) {
                    return;
                }

                e.preventDefault();

                const semesterText = activeYear.semester.charAt(0).toUpperCase() + activeYear.semester.slice(1);
                window.showConfirm({
                    title: 'Aktifkan Tahun Ajaran Baru?',
                    message: `Tahun ajaran ${activeYear.year} (${semesterText}) saat ini sedang aktif. Jika Anda melanjutkan, tahun ajaran tersebut akan dinonaktifkan otomatis. Lanjutkan?`,
                    confirmText: 'Ya, Aktifkan',
                    formId: 'create-academic-form',
                    type: 'warning'
                });
            }
        });
    });
</script>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 pb-safe">
    <div class="flex items-center gap-3 max-w-lg mx-auto">
        <a href="{{ route('admin.academic_years.index') }}" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
            <i class="fa-solid fa-xmark text-slate-400"></i> Batal
        </a>
        <button type="submit" form="create-academic-form" class="flex-[2] flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-br from-primary to-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all border-b-2 border-blue-900">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Data
        </button>
    </div>
</div>
@endsection
