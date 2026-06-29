@extends('layouts.dashboard')

@section('title', 'Edit Wali Murid - ' . $user->name . ' - e-Rapor')

@section('back_url', route('admin.parents.index'))

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
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shadow-md border border-white">
                    <i class="fa-solid fa-user-pen text-[10px]"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-user-gear text-[9px]"></i> ID Pengguna: #{{ $user->id }}
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Edit Data Wali Murid</h3>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-base text-secondary opacity-95"></i>
                        <span class="truncate">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-shield text-base text-secondary opacity-95"></i>
                        <span>Akses: <strong class="text-white">{{ ucfirst($user->role) }}</strong></span>
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
        <a href="{{ route('admin.parents.index') }}"
           class="inline-flex items-center gap-4 bg-white rounded-2xl shadow-md border border-slate-100 p-4 hover:bg-slate-50 transition-colors group">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="pr-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none mt-1 block">Kembali ke Daftar Wali Murid</span>
            </div>
        </a>
    </div>

    <form id="edit-parent-form" method="POST" action="{{ route('admin.parents.update', $user->id) }}" x-data="{ relation: '{{ old('relation', $user->parent->relation ?? '') }}', showModal: false }" class="space-y-6 px-4 sm:px-0">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Perbarui Informasi Akun</h4>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Nama Lengkap Wali Murid</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-user text-sm"></i>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('name') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Alamat Email Aktif</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('email') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="contoh: wali.murid@email.com">
                        </div>
                        @error('email')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="relation" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Hubungan Keluarga</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-people-arrows text-sm"></i>
                            </div>
                            <select name="relation" id="relation" x-model="relation" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('relation') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled>Pilih Hubungan Keluarga</option>
                                <option value="ayah" {{ old('relation', $user->parent->relation ?? '') == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                <option value="ibu" {{ old('relation', $user->parent->relation ?? '') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="wali" {{ old('relation', $user->parent->relation ?? '') == 'wali' ? 'selected' : '' }}>Wali</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('relation')
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
                            <input type="text" name="telp" id="telp" value="{{ old('telp', $user->parent->telp ?? '') }}" required
                                class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 border @error('telp') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm"
                                placeholder="Contoh: 081234567890">
                        </div>
                        @error('telp')
                            <p class="text-xs font-bold text-danger mt-1.5 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2 border-t border-slate-100 pt-6 mt-2"
                         x-data="{
                             search: '',
                             students: [
                                 @foreach($students as $student)
                                     { 
                                         id: {{ $student->id }}, 
                                         name: '{{ addslashes($student->name) }}', 
                                         nisn: '{{ $student->nisn }}', 
                                         classroom: '{{ $student->classroom->name ?? '-' }}', 
                                         checked: {{ in_array($student->id, old('student_ids', $user->parent ? $user->parent->students->pluck('id')->toArray() : [])) ? 'true' : 'false' }},
                                         parents: [
                                             @foreach($student->parents as $p)
                                                 @if($user->parent && $p->id !== $user->parent->id)
                                                     { id: {{ $p->id }}, name: '{{ addslashes($p->user->name ?? '-') }}', relation: '{{ $p->relation }}' },
                                                 @endif
                                             @endforeach
                                         ]
                                     },
                                 @endforeach
                             ],
                             get filteredStudents() {
                                 if (!this.search) return this.students;
                                 const q = this.search.toLowerCase();
                                 return this.students.filter(s => s.name.toLowerCase().includes(q) || s.nisn.includes(q));
                             },
                             get selectedCount() {
                                 return this.students.filter(s => s.checked).length;
                             },
                             isEligible(student) {
                                 if (student.checked) return true;
                                 if (student.parents.length >= 2) return false;
                                 return !student.parents.some(p => p.relation === relation);
                             },
                             getIneligibilityReason(student) {
                                 if (student.parents.length >= 2) {
                                     return 'Maksimal 2 orang tua/wali tercapai';
                                 }
                                 const matched = student.parents.find(p => p.relation === relation);
                                 if (matched) {
                                     return 'Hubungan ' + relation.toUpperCase() + ' sudah ada: ' + matched.name;
                                 }
                                 return '';
                             }
                         }">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Hubungkan Akun dengan Siswa</span>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5" x-text="selectedCount + ' Siswa terpilih'">0 Siswa terpilih</span>
                                </div>
                            </div>
                            <button type="button" @click="showModal = true" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-700 rounded-lg transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-user-plus text-[10px]"></i>
                                Pilih Siswa
                            </button>
                        </div>
                        
                        <!-- Display selected students -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4" x-show="selectedCount > 0">
                            <template x-for="student in students.filter(s => s.checked)" :key="student.id">
                                <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-bold text-slate-800 block truncate" x-text="student.name"></span>
                                        <span class="text-[10px] text-slate-400 font-medium block mt-0.5" x-text="'NISN: ' + student.nisn + ' • Kelas: ' + student.classroom"></span>
                                    </div>
                                    <button type="button" @click="student.checked = false" class="text-xs font-bold text-danger hover:text-red-700 p-1 cursor-pointer transition-colors">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                    <input type="hidden" name="student_ids[]" :value="student.id">
                                </div>
                            </template>
                        </div>

                        <!-- Alpine Modal -->
                        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <!-- Background overlay -->
                                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <!-- Modal panel -->
                                <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                     class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                                    
                                    <!-- Header -->
                                    <div class="bg-primary text-white p-6 relative select-none">
                                        <div class="absolute -top-12 -left-12 w-32 h-32 border-4 border-dashed border-white/10 rounded-full pointer-events-none"></div>
                                        <div class="relative z-10 flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-black uppercase tracking-wider">Pilih Siswa / Anak</h3>
                                                <p class="text-[9px] text-white/80 font-medium mt-1">Cari dan pilih siswa untuk dihubungkan ke wali murid.</p>
                                            </div>
                                            <button type="button" @click="showModal = false" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition-colors cursor-pointer text-white">
                                                <i class="fa-solid fa-xmark text-sm"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Search and Filters -->
                                    <div class="p-6 pb-3 border-b border-slate-100 space-y-4 bg-slate-50/50">
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                            </div>
                                            <input type="text" x-model="search" placeholder="Cari nama atau NISN siswa..." 
                                                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-primary rounded-lg text-xs font-medium text-slate-800 focus:outline-none transition-all shadow-sm">
                                        </div>
                                        <div class="flex items-center justify-between text-[9px] font-black text-slate-400 uppercase tracking-wider pl-1">
                                            <span>Hasil Pencarian</span>
                                            <span x-text="selectedCount + ' Siswa terpilih'">0 Siswa terpilih</span>
                                        </div>
                                    </div>

                                    <!-- Students list -->
                                    <div class="p-6 max-h-80 overflow-y-auto space-y-2.5 custom-scrollbar">
                                        <template x-for="student in filteredStudents" :key="student.id">
                                            <div :class="!isEligible(student) ? 'opacity-60 bg-slate-50 border-slate-100' : (student.checked ? 'border-primary/30 bg-primary/5' : 'border-slate-100 bg-white hover:bg-slate-50')" 
                                                 class="flex items-start gap-3 p-3 border rounded-xl transition-all relative">
                                                
                                                <input type="checkbox" x-model="student.checked" :disabled="!isEligible(student)" 
                                                       class="rounded text-primary focus:ring-primary border-slate-300 w-4 h-4 cursor-pointer mt-0.5">
                                                
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-xs font-bold text-slate-800 block truncate" x-text="student.name"></span>
                                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5" x-text="'NISN: ' + student.nisn + ' • Kelas: ' + student.classroom"></span>
                                                    
                                                    <!-- Ineligible warnings -->
                                                    <template x-if="!isEligible(student)">
                                                        <div class="flex items-center gap-1.5 mt-1.5 text-[9px] font-black text-amber-600 uppercase tracking-wide">
                                                            <i class="fa-solid fa-triangle-exclamation text-[8px]"></i>
                                                            <span x-text="getIneligibilityReason(student)"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="filteredStudents.length === 0" class="text-center py-10 text-xs font-semibold text-slate-400 bg-slate-50 border border-dashed border-slate-200 rounded-2xl">
                                            Siswa tidak ditemukan.
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                                        <button type="button" @click="showModal = false" 
                                                class="px-4 py-2 bg-primary hover:bg-opacity-95 text-xs font-black text-white rounded-xl transition-all uppercase tracking-wider cursor-pointer shadow-sm shadow-primary/10">
                                            Selesai
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @error('student_ids')
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
                <i class="fa-solid fa-circle-info text-lg"></i>
            </div>
            <div class="flex-1">
                <h5 class="font-extrabold text-xs uppercase tracking-wide text-primary">Catatan Integrasi Sistem</h5>
                <p class="text-xs text-slate-600 mt-0.5 leading-normal">Mengubah alamat email akan berdampak pada kredensial log masuk pengguna. Pastikan email yang dimasukkan berstatus aktif agar wali murid tetap dapat menerima laporan perkembangan akademik secara berkala. Jika email diubah, sistem secara otomatis akan mengirimkan ulang email aktivasi akun ke email yang baru.</p>
            </div>
        </div>

        {{-- Desktop-only submit buttons (hidden on mobile, bottom bar handles mobile) --}}
        <div class="hidden md:flex items-center justify-end gap-4 pt-2 pb-6">
            <a href="{{ route('admin.parents.index') }}"
               class="px-5 py-3 rounded-xl border border-slate-200 text-xs font-black text-slate-400 uppercase tracking-wider hover:bg-slate-50 transition-all text-center">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                Perbarui Data Wali Murid
            </button>
        </div>

    </form>
</div>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 pb-safe">
    <div class="flex items-center gap-3 max-w-lg mx-auto">
        <a href="{{ route('admin.parents.index') }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
            <i class="fa-solid fa-xmark text-slate-400"></i>
            Batal
        </a>
        <button type="submit" form="edit-parent-form"
                class="flex-[2] flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-br from-primary to-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all border-b-2 border-blue-900">
            <i class="fa-solid fa-floppy-disk"></i>
            Perbarui Data 
        </button>
    </div>
</div>
@endsection
