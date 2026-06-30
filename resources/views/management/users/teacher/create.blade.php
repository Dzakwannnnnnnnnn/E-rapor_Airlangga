@extends('layouts.dashboard')

@section('title', 'Tambah Guru Baru - e-Rapor')

@section('back_url', route('admin.teachers.index'))

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
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-shield-halved text-[9px]"></i> Hak Akses: Admin
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Tambah Guru Baru</h3>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                        <span>Panel Kontrol Kependidikan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-base text-secondary opacity-95"></i>
                        <span>Tahun Ajaran 2025/2026</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10"
             style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);">
        </div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10"
             style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);">
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-6 space-y-6">

    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0">
        <a href="{{ route('admin.teachers.index') }}"
        class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Daftar Guru</span>
            </div>
        </a>
    </div>

    <form id="teacher-form" method="POST" action="{{ route('admin.teachers.store') }}" class="space-y-6 px-4 sm:px-0">
        @csrf

        <div class="space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Formulir Identitas Pendidik</h4>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">

                <div class="space-y-2">
                    <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('name') border-danger focus:ring-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                            placeholder="Masukkan nama lengkap beserta gelar akademik">
                    </div>
                    @error('name')
                        <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="space-y-2">
                        <label for="nip" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Nomor Induk Pegawai (NIP)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-id-card text-sm"></i>
                            </div>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                                maxlength="16"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,16)"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('nip') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="Contoh: 19850330XXXXXXXXXX">
                        </div>
                        @error('nip')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Alamat Email Instansi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('email') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="nama.guru@sekolah.sch.id">
                        </div>
                        @error('email')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="gender" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Jenis Kelamin</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-venus-mars text-sm"></i>
                            </div>
                            <select name="gender" id="gender" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('gender') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('gender')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="telp" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Nomor Telepon / WA</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </div>
                            <input type="text" name="telp" id="telp" value="{{ old('telp') }}" required
                                maxlength="16"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,16)"
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('telp') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="Contoh: 081234567890">
                        </div>
                        @error('telp')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-primary text-slate-900 rounded-2xl p-4 shadow-sm flex items-center gap-4 select-none">
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-envelope-circle-check text-lg"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-extrabold text-xs uppercase tracking-wide text-primary">Aktivasi Akun via Email</h5>
                <p class="text-xs text-slate-600 mt-0.5 leading-normal">Setelah data disimpan, sistem akan <span class="font-bold text-slate-800">otomatis mengirim email aktivasi</span> ke alamat instansi guru. Guru cukup klik link di email untuk membuat kata sandi mereka sendiri. Link berlaku <span class="font-bold text-slate-800">24 jam</span>.</p>
            </div>
        </div>

        {{-- Desktop only buttons --}}
        <div class="hidden md:flex items-center justify-end gap-4 pt-2 pb-4">
            <a href="{{ route('admin.teachers.index') }}"
               class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-50 transition-all text-center">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                Simpan Data Guru
            </button>
        </div>

    </form>
</div>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.10)]">
    <div class="flex items-center gap-3 px-4 py-3">
        <a href="{{ route('admin.teachers.index') }}"
           class="flex-1 flex items-center justify-center gap-2 h-12 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-wider transition-all active:scale-95">
            <i class="fa-solid fa-arrow-left text-sm"></i>
            <span>Batal</span>
        </a>
        <button form="teacher-form" type="submit"
                class="flex-[2] flex items-center justify-center gap-2 h-12 rounded-xl bg-primary hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-primary/25 active:scale-95">
            <i class="fa-solid fa-floppy-disk text-sm"></i>
            <span>Simpan Data Guru</span>
        </button>
    </div>
</div>
@endsection
