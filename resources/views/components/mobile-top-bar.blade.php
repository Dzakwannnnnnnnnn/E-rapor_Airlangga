@props([
    'backUrl' => null,
    'title' => null,
])

@php
    $menus = [];
    if (auth()->check()) {
        $role = session('active_role', auth()->user()->role);
        
        // Base menus for all logged in users
        $menus[] = [
            'title' => 'Profil Saya',
            'url' => route('profile.edit'),
            'icon' => 'fa-solid fa-user',
            'bg' => 'bg-primary/10 text-primary',
            'description' => 'Lihat dan atur profil akun Anda',
            'keywords' => ['profil', 'profile', 'akun', 'saya', 'avatar', 'foto', 'biodata', 'detail']
        ];
        $menus[] = [
            'title' => 'Edit Detail Profil',
            'url' => route('profile.edit-details'),
            'icon' => 'fa-solid fa-user-pen',
            'bg' => 'bg-blue-50 text-blue-600',
            'description' => 'Ubah nama lengkap dan email Anda',
            'keywords' => ['edit profil', 'ubah nama', 'ubah email', 'biodata', 'identitas', 'detail']
        ];
        $menus[] = [
            'title' => 'Ubah Password',
            'url' => route('profile.change-password'),
            'icon' => 'fa-solid fa-key',
            'bg' => 'bg-amber-50 text-amber-600',
            'description' => 'Perbarui kata sandi akun Anda',
            'keywords' => ['ubah password', 'ganti password', 'kata sandi', 'keamanan', 'sandi', 'update password']
        ];
        
        if ($role === 'admin') {
            $menus[] = [
                'title' => 'Home / Dashboard',
                'url' => route('admin.dashboard'),
                'icon' => 'fa-solid fa-house',
                'bg' => 'bg-emerald-50 text-emerald-600',
                'description' => 'Halaman utama dashboard admin',
                'keywords' => ['home', 'beranda', 'dashboard', 'utama', 'awal', 'admin']
            ];
            $menus[] = [
                'title' => 'Pusat Manajemen',
                'url' => route('admin.management.index'),
                'icon' => 'fa-solid fa-grip',
                'bg' => 'bg-purple-50 text-purple-600',
                'description' => 'Kelola seluruh data sistem e-Rapor',
                'keywords' => ['management', 'manajemen', 'pengaturan', 'kelola', 'admin', 'pusat', 'modul']
            ];
            $menus[] = [
                'title' => 'Kelola Guru Pengajar',
                'url' => route('admin.teachers.index'),
                'icon' => 'fa-solid fa-chalkboard-user',
                'bg' => 'bg-indigo-50 text-indigo-600',
                'description' => 'Manajemen data guru pengajar',
                'keywords' => ['guru', 'pengajar', 'teacher', 'kelola guru', 'tambah guru', 'data guru', 'nip', 'dosen']
            ];
            $menus[] = [
                'title' => 'Kelola Wali Murid',
                'url' => route('admin.parents.index'),
                'icon' => 'fa-solid fa-users-rays',
                'bg' => 'bg-pink-50 text-pink-600',
                'description' => 'Manajemen data orang tua / wali murid',
                'keywords' => ['wali', 'orang tua', 'parent', 'murid', 'ibu', 'ayah', 'kelola wali', 'tambah wali', 'wali murid']
            ];
            $menus[] = [
                'title' => 'Pengesahan Rapor',
                'url' => route('admin.report-cards.index'),
                'icon' => 'fa-solid fa-stamp',
                'bg' => 'bg-amber-50 text-amber-600',
                'description' => 'Verifikasi dan sahkan usulan rapor dari wali kelas',
                'keywords' => ['sahkan', 'pengesahan', 'rapor', 'laporan', 'validasi', 'tolak', 'setujui']
            ];
        } elseif ($role === 'teacher') {
            $menus[] = [
                'title' => 'Home / Dashboard',
                'url' => route('teacher.dashboard'),
                'icon' => 'fa-solid fa-house',
                'bg' => 'bg-emerald-50 text-emerald-600',
                'description' => 'Halaman utama dashboard guru',
                'keywords' => ['home', 'beranda', 'dashboard', 'utama', 'awal', 'guru']
            ];
            $menus[] = [
                'title' => 'Kelas Saya',
                'url' => route('teacher.kelas_saya.index'),
                'icon' => 'fa-solid fa-book-open',
                'bg' => 'bg-blue-50 text-blue-600',
                'description' => 'Lihat rombongan belajar dan input nilai mata pelajaran',
                'keywords' => ['kelas', 'mapel', 'nilai', 'kkm', 'siswa', 'mengajar']
            ];
            $menus[] = [
                'title' => 'Laporan Nilai',
                'url' => route('teacher.laporan.index'),
                'icon' => 'fa-solid fa-file-lines',
                'bg' => 'bg-rose-50 text-rose-600',
                'description' => 'Lihat detail capaian nilai per rombel',
                'keywords' => ['laporan', 'nilai', 'rapor', 'draft', 'kirim']
            ];
            if (auth()->user()->teacher && auth()->user()->teacher->classroomAsHomeroom()->exists()) {
                $menus[] = [
                    'title' => 'Wali Kelas',
                    'url' => route('teacher.homeroom.index'),
                    'icon' => 'fa-solid fa-chalkboard-user',
                    'bg' => 'bg-indigo-50 text-indigo-600',
                    'description' => 'Kelola kehadiran, catatan, dan pengesahan rapor kelas Anda',
                    'keywords' => ['wali', 'kelas', 'rapor', 'sahkan', 'absen', 'catatan', 'homeroom']
                ];
            }
        } elseif ($role === 'parent') {
            $menus[] = [
                'title' => 'Home / Dashboard',
                'url' => route('parent.dashboard'),
                'icon' => 'fa-solid fa-house',
                'bg' => 'bg-emerald-50 text-emerald-600',
                'description' => 'Halaman utama dashboard wali murid',
                'keywords' => ['home', 'beranda', 'dashboard', 'utama', 'awal', 'wali', 'orang tua']
            ];
        }

        // Add Logout
        $menus[] = [
            'title' => 'Keluar / Logout',
            'url' => '#logout',
            'icon' => 'fa-solid fa-arrow-right-from-bracket',
            'bg' => 'bg-red-50 text-red-600',
            'description' => 'Keluar dari sesi akun saat ini',
            'keywords' => ['keluar', 'logout', 'exit', 'signout', 'log off']
        ];
    }
@endphp

@if($backUrl)
    <!-- BACK BUTTON TOP BAR (DANA Concept) -->
    <div class="md:hidden flex items-center bg-primary px-4 h-16 border-b border-white/10 sticky top-0 z-30 shadow-md gap-4">
        <a href="{{ $backUrl }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 text-white transition-colors active:scale-95 duration-150 shrink-0">
            <i class="fa-solid fa-chevron-left text-lg"></i>
        </a>
        <h1 class="text-white font-extrabold text-base truncate uppercase tracking-wider">
            {{ $title }}
        </h1>
    </div>
@else
    <!-- SEARCH STATIC / ACTIVE MENU SEARCH TOP BAR -->
    <div class="md:hidden bg-primary sticky top-0 z-30 shadow-md border-b border-white/10"
         x-data="{
             searchQuery: '',
             isFocused: false,
             menus: {{ json_encode($menus) }},
             get filteredMenus() {
                 if (!this.searchQuery.trim()) {
                     return this.menus.slice(0, 4);
                 }
                 const query = this.searchQuery.toLowerCase().trim();
                 return this.menus.filter(menu => {
                     return menu.title.toLowerCase().includes(query) ||
                            menu.description.toLowerCase().includes(query) ||
                            menu.keywords.some(keyword => keyword.toLowerCase().includes(query));
                 });
             },
             clearSearch() {
                 this.searchQuery = '';
             },
             handleItemClick(url) {
                 if (url === '#logout') {
                     const logoutForm = document.getElementById('global-logout-form');
                     if (logoutForm) {
                         logoutForm.submit();
                     }
                 } else {
                     window.location.href = url;
                 }
             }
         }"
         x-on:click.outside="isFocused = false">
        
        <div class="flex items-center justify-between px-4 h-16 gap-3">
            <div class="relative flex-1 flex items-center bg-white/10 focus-within:bg-white border border-white/10 focus-within:border-white rounded-full transition-all px-3 py-1.5 group">
                <i class="fa-solid fa-magnifying-glass text-white/60 group-focus-within:text-slate-400 text-xs mr-2 transition-colors shrink-0"></i>
                <input type="text" 
                       placeholder="Cari di e-Rapor..." 
                       x-model="searchQuery"
                       x-on:focus="isFocused = true"
                       class="w-full bg-transparent border-0 p-0 text-xs text-white group-focus-within:text-slate-800 placeholder-white/60 group-focus-within:placeholder-slate-400 focus:outline-none focus:ring-0 focus:border-0 transition-colors">
                
                <!-- Clear Button -->
                <button x-show="searchQuery.length > 0" 
                        x-on:click="clearSearch()"
                        class="absolute right-3 text-white/60 group-focus-within:text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <!-- SEARCH RESULTS SUGGESTION PANEL -->
        <div x-show="isFocused"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-[-10px]"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-[-10px]"
             class="absolute top-16 left-0 right-0 bg-white/95 backdrop-blur-md shadow-2xl border-t border-slate-100 flex flex-col z-50 max-h-[calc(100vh-4rem)] overflow-y-auto rounded-b-2xl"
             style="display: none;">
            
            <div class="p-4 flex flex-col gap-3">
                <!-- Section Title -->
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">
                    <span x-show="!searchQuery.trim()">Rekomendasi Pintasan</span>
                    <span x-show="searchQuery.trim()">Hasil Pencarian Menu</span>
                </div>

                <!-- Suggestions List -->
                <div class="flex flex-col gap-2">
                    <template x-for="menu in filteredMenus" :key="menu.title">
                        <div x-on:click="handleItemClick(menu.url)"
                             class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 border border-slate-50 hover:border-slate-100 transition-all cursor-pointer group active:scale-[0.98]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm" :class="menu.bg">
                                    <i :class="menu.icon + ' text-base'"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 text-xs leading-tight group-hover:text-primary transition-colors" x-text="menu.title"></span>
                                    <span class="text-[10px] text-slate-400 mt-0.5 leading-normal" x-text="menu.description"></span>
                                </div>
                            </div>
                            <div class="text-slate-300 group-hover:text-primary transition-colors pr-1">
                                <i class="fa-solid fa-chevron-right text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="filteredMenus.length === 0" 
                         class="py-8 px-4 text-center flex flex-col items-center justify-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-2">
                            <i class="fa-solid fa-magnifying-glass-minus text-lg"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-500">Menu tidak ditemukan</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Coba kata kunci lain (contoh: guru, wali, password)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
