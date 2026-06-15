@extends('layouts.app')

@section('title', 'E Rapor')

@section('content')

<header x-data="{
        mobileMenuOpen: false,
        activeSection: 'beranda',
        checkActive() {
            const sections = ['beranda', 'fitur', 'alur', 'faq'];
            let current = 'beranda';
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    /* Deteksi jika elemen mendekati area pandang atas (offset 150px) */
                    if (rect.top <= 150) {
                        current = id;
                    }
                }
            });
            this.activeSection = current;
        }
    }"
    @scroll.window="checkActive()"
    x-init="checkActive()"
    class="fixed top-0 left-0 w-full bg-[#002B80] z-50 shadow-lg border-b border-white/10">
    <div class="max-w-7xl mx-auto flex justify-between items-center relative h-10 lg:h-10 transition-all">

        <div class="bg-white h-full flex items-center pl-4 pr-8 relative"
             style="clip-path: polygon(0 0, 100% 0, calc(100% - 12px) 100%, 0 100%);">
            <div class="flex items-center gap-2">
                <div class="flex items-center justify-center shrink-0">
                    <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="Logo" class="w-7 h-7">
                </div>
                <div class="text-slate-900 leading-none">
                    <h1 class="text-[11px] font-black tracking-tight uppercase">e-Rapor</h1>
                </div>
            </div>
            <div class="absolute right-[1px] top-0 bottom-0 w-0.5 bg-[#FFB800]"></div>
        </div>

        <div class="pr-4 lg:pr-8 flex items-center gap-5">

            <nav class="hidden lg:flex gap-6 text-[10px] font-bold tracking-wider uppercase">
                <a href="#beranda"
                   :class="activeSection === 'beranda' ? 'text-[#FFB800] border-b-2 border-[#FFB800]' : 'text-white/85 border-b-2 border-transparent hover:text-[#FFB800]'"
                   class="pb-0.5 transition-colors">Beranda</a>
                <a href="#fitur"
                   :class="activeSection === 'fitur' ? 'text-[#FFB800] border-b-2 border-[#FFB800]' : 'text-white/85 border-b-2 border-transparent hover:text-[#FFB800]'"
                   class="pb-0.5 transition-colors">Fitur</a>
                <a href="#alur"
                   :class="activeSection === 'alur' ? 'text-[#FFB800] border-b-2 border-[#FFB800]' : 'text-white/85 border-b-2 border-transparent hover:text-[#FFB800]'"
                   class="pb-0.5 transition-colors">Alur</a>
                <a href="#faq"
                   :class="activeSection === 'faq' ? 'text-[#FFB800] border-b-2 border-[#FFB800]' : 'text-white/85 border-b-2 border-transparent hover:text-[#FFB800]'"
                   class="pb-0.5 transition-colors">Bantuan</a>
            </nav>

            <a href="/login" class="hidden lg:flex items-center gap-2 bg-[#FFB800] hover:bg-yellow-500 text-slate-950 font-black text-[10px] uppercase tracking-widest px-4 py-2 rounded-lg transition-all shadow-md">
                <i class="fa-solid fa-right-to-bracket"></i>
                Login
            </a>

            <button @click="mobileMenuOpen = true"
                    class="block lg:hidden text-white/85 hover:text-[#FFB800] focus:outline-none transition-colors">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] lg:hidden" style="display: none;">

        <div x-show="mobileMenuOpen"
             @click="mobileMenuOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

        <div x-show="mobileMenuOpen"
             @click.away="mobileMenuOpen = false"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="absolute top-0 right-0 h-full w-full max-w-[280px] bg-[#002B80] shadow-2xl border-l border-white/10 flex flex-col">

            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10 shrink-0">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="Logo" class="w-6 h-6">
                    <span class="text-[12px] font-black text-white tracking-tight uppercase">Menu</span>
                </div>
                <button @click="mobileMenuOpen = false" class="text-white/70 hover:text-[#FFB800] focus:outline-none transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-8">
                <nav class="flex flex-col gap-6 text-[11px] font-bold tracking-wider uppercase">
                    <a href="#beranda" @click="mobileMenuOpen = false"
                       :class="activeSection === 'beranda' ? 'text-[#FFB800]' : 'text-white/85 hover:text-[#FFB800] transition-colors'"
                       class="flex items-center gap-4">
                        <i class="fa-solid fa-house w-4 text-center"></i> Beranda
                    </a>
                    <a href="#fitur" @click="mobileMenuOpen = false"
                       :class="activeSection === 'fitur' ? 'text-[#FFB800]' : 'text-white/85 hover:text-[#FFB800] transition-colors'"
                       class="flex items-center gap-4">
                        <i class="fa-solid fa-star w-4 text-center"></i> Fitur
                    </a>
                    <a href="#alur" @click="mobileMenuOpen = false"
                       :class="activeSection === 'alur' ? 'text-[#FFB800]' : 'text-white/85 hover:text-[#FFB800] transition-colors'"
                       class="flex items-center gap-4">
                        <i class="fa-solid fa-route w-4 text-center"></i> Alur
                    </a>
                    <a href="#faq" @click="mobileMenuOpen = false"
                       :class="activeSection === 'faq' ? 'text-[#FFB800]' : 'text-white/85 hover:text-[#FFB800] transition-colors'"
                       class="flex items-center gap-4">
                        <i class="fa-solid fa-circle-question w-4 text-center"></i> Bantuan
                    </a>
                </nav>
            </div>

            <div class="p-6 border-t border-white/10 shrink-0">
                <a href="/login" class="flex justify-center items-center gap-2 bg-[#FFB800] hover:bg-yellow-500 text-slate-950 font-black text-[11px] uppercase tracking-widest px-4 py-3.5 rounded-lg transition-all shadow-md w-full">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk Portal
                </a>
            </div>

        </div>
    </div>
</header>

<div class="min-h-screen bg-[#F4F6F9] text-slate-800 font-sans selection:bg-[#003399]/25 selection:text-[#003399] flex flex-col">

    <div class="relative bg-[#003399] pt-0 pb-20 lg:pb-28 overflow-hidden z-0 mt-10">

        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-10"></div>

        <div class="absolute top-16 right-12 opacity-25 hidden lg:grid grid-cols-6 gap-2 pointer-events-none z-10">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute bottom-20 left-6 opacity-20 grid grid-cols-4 gap-1.5 pointer-events-none z-10">
            @for ($i = 0; $i < 12; $i++)
                <div class="w-1 h-1 bg-[#FFB800] rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 -left-6 w-12 h-12 border-[3px] border-solid border-[#FFB800]/40 rounded-full pointer-events-none z-10"></div>

        <div class="absolute top-10 right-[15%] w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 mt-12 lg:mt-20 relative z-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="text-left lg:text-left lg:col-span-7 relative">
                <div class="absolute -top-6 -left-4 w-8 h-8 border-t-2 border-l-2 border-[#FFB800]/60 hidden lg:block rounded-tl-md"></div>


                <h2 class="text-3xl lg:text-5xl font-black text-white tracking-tight leading-none mb-4 uppercase">
                    SISTEM MONITORING <br>
                    <span class="text-[#FFB800]">RAPOR DIGITAL</span> Siswa
                </h2>
                <p class="text-xs lg:text-sm text-white/75 max-w-md mx-auto lg:mx-0 font-normal leading-relaxed mb-8">
                    e-Rapor adalah sistem digital untuk mengelola nilai, kehadiran, dan laporan hasil belajar siswa secara transparan dan terstruktur di SMK TI Airlangga Samarinda.
                </p>

                <form action="#" method="POST" class="max-w-md mx-auto lg:mx-0" autocomplete="off">
                    <div class="bg-white p-1.5 flex items-center shadow-2xl rounded-xl border-b-4 border-[#FFB800] focus-within:ring-4 focus-within:ring-[#FFB800]/20 transition-all">
                        <span class="text-slate-400 shrink-0 mx-3">
                            <i class="fa-solid fa-id-card text-base text-[#003399]"></i>
                        </span>
                        <input type="text" name="nisn" id="nisn" placeholder="Masukkan NISN Siswa..."
                            class="w-full bg-transparent text-slate-900 text-sm py-1 outline-none font-bold placeholder:text-slate-400">

                        <button type="submit"
                            class="bg-[#003399] hover:bg-[#001F5C] text-white font-black text-xs uppercase tracking-wider px-3.5 py-3.5 rounded-lg transition-all shrink-0 flex items-center gap-2 border-l border-slate-100">
                            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#FFB800]"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-5 relative group">
                <div class="absolute inset-0 bg-[#FFB800] translate-x-2 translate-y-2 z-0 transition-transform group-hover:translate-x-1 group-hover:translate-y-1 rounded-2xl"></div>

                <div class="relative bg-[#002266] p-6 text-white h-full flex flex-col justify-center z-10 rounded-2xl shadow-lg"
                    style="clip-path: polygon(0 0, 100% 0, calc(100% - 12px) 100%, 0 100%);">

                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-[9px] bg-[#FFB800] text-slate-950 font-black px-2 py-0.5 rounded uppercase tracking-widest">Agenda Akademik</span>
                        <span class="text-[9px] text-white/50 font-mono">SEMESTER GENAP 2026</span>
                    </div>

                    <h3 class="text-lg font-black uppercase tracking-tight leading-tight">
                        Pembagian <span class="text-[#FFB800]">Rapor Digital</span>
                    </h3>
                    <p class="text-xs text-white/70 mb-6 font-normal">
                    Rapor akan tersedia dan dapat mulai diakses oleh siswa pada hitungan mundur berikut:
                    </p>

                    <div class="grid grid-cols-4 gap-2 text-center" id="countdown-container">
                        <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                            <span id="cd-days" class="block text-xl font-black font-mono text-[#FFB800]">00</span>
                            <span class="text-[8px] uppercase tracking-wider opacity-60">Hari</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                            <span id="cd-hours" class="block text-xl font-black font-mono text-[#FFB800]">00</span>
                            <span class="text-[8px] uppercase tracking-wider opacity-60">Jam</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                            <span id="cd-minutes" class="block text-xl font-black font-mono text-[#FFB800]">00</span>
                            <span class="text-[8px] uppercase tracking-wider opacity-60">Menit</span>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-lg py-2.5 backdrop-blur-xs">
                            <span id="cd-seconds" class="block text-xl font-black font-mono text-[#FFB800]">00</span>
                            <span class="text-[8px] uppercase tracking-wider opacity-60">Detik</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 bg-[#FFB800] z-10"
             style="clip-path: polygon(0 100%, 100% 70%, 100% 100%, 0 100%);">
        </div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-[#F4F6F9] z-10"
             style="clip-path: polygon(0 100%, 100% 40%, 100% 100%, 0 100%);">
        </div>
    </div>

    <main class="flex-grow max-w-7xl mx-auto px-6 lg:px-12 w-full pt-10 pb-20 space-y-16 relative z-20">

        {{-- <section class="bg-white p-6 rounded-xl border-l-4 border-[#003399] shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-1 bg-[#FFB800]"></div>

            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-5 text-center lg:text-left">
                <span class="text-[#003399]">01.</span> Menu Akses Cepat Informasi
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $features = [
                        ['icon' => 'fa-chart-column', 'label' => 'Grafik Capaian Nilai'],
                        ['icon' => 'fa-clipboard-user', 'label' => 'Presensi Absensi Siswa'],
                        ['icon' => 'fa-file-signature', 'label' => 'Unduh Lembar Rapor'],
                        ['icon' => 'fa-bullhorn', 'label' => 'Papan Pengumuman Kurikulum'],
                    ];
                @endphp

                @foreach ($features as $feature)
                <div class="bg-[#F8FAFC] p-4 flex items-center justify-between border border-slate-200/60 rounded-xl hover:border-[#003399] hover:bg-white hover:shadow-md transition-all cursor-pointer group">
                    <div class="flex items-center gap-3.5">
                        <div class="text-[#003399] group-hover:text-[#FFB800] transition-colors shrink-0">
                            <i class="fa-solid {{ $feature['icon'] }} text-base"></i>
                        </div>
                        <span class="font-bold text-xs text-slate-700 tracking-tight group-hover:text-slate-900 transition-colors">{{ $feature['label'] }}</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 group-hover:text-[#003399] group-hover:translate-x-1 transition-all"></i>
                </div>
                @endforeach
            </div>
        </section> --}}

        <section id="fitur" class="max-w-7xl mx-auto px-6 py-20">
            <div class="max-w-7xl mx-auto px-6 mb-16 text-center">
                <h2 class="text-2xl font-black text-primary tracking-tight uppercase">Fitur Unggulan e-Rapor</h2>
                <div class="w-12 h-1 bg-[#FFB800] mx-auto mt-4 mb-2"></div>
                <p class="text-sm text-slate-500 font-medium">Berbagai fitur digital untuk mendukung pengelolaan nilai dan laporan hasil belajar siswa.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">

                {{-- <div class="relative group">
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-[#FFB800] rounded-2xl -z-10"></div>
                    <div class="bg-[#003399] p-8 rounded-2xl h-full border border-white/10 flex flex-col items-center text-center text-white">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-6 text-[#FFB800] text-2xl">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <h4 class="font-bold text-sm mb-3 uppercase">Pengelolaan Nilai Digital</h4>
                        <p class="text-[11px] text-white/70 leading-relaxed">Input dan kelola nilai siswa secara mudah dan terstruktur.</p>
                    </div>
                </div> --}}

                <div class="relative group">
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-[#FFB800] rounded-2xl -z-10"></div>
                    <div class="bg-[#003399] p-8 rounded-2xl h-full border border-white/10 flex flex-col items-center text-center text-white">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-6 text-[#FFB800] text-2xl">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <h4 class="font-bold text-sm mb-3 uppercase">Rapor Digital</h4>
                        <p class="text-[11px] text-white/70 leading-relaxed">Akses dan cetak rapor secara cepat dalam format digital.</p>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-[#FFB800] rounded-2xl -z-10"></div>
                    <div class="bg-[#003399] p-8 rounded-2xl h-full border border-white/10 flex flex-col items-center text-center text-white">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-6 text-[#FFB800] text-2xl">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h4 class="font-bold text-sm mb-3 uppercase">Monitoring Siswa</h4>
                        <p class="text-[11px] text-white/70 leading-relaxed">Melihat perkembangan capaian belajar siswa secara transparan.</p>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-[#FFB800] rounded-2xl -z-10"></div>
                    <div class="bg-[#003399] p-8 rounded-2xl h-full border border-white/10 flex flex-col items-center text-center text-white">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-6 text-[#FFB800] text-2xl">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <h4 class="font-bold text-sm mb-3 uppercase">Rekap Kehadiran</h4>
                        <p class="text-[11px] text-white/70 leading-relaxed">Data kehadiran siswa terintegrasi dalam laporan rapor.</p>
                    </div>
                </div>

            </div>
        </section>

        <section id="alur" class="max-w-7xl mx-auto px-6 lg:px-12 w-full mb-24">
            <div class="mb-12">
                <h3 class="text-xs font-black text-[#003399] uppercase tracking-widest text-center lg:text-left">
                    Alur Penggunaan
                </h3>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight text-center lg:text-left mt-1">
                    Cara Melihat Rapor Digital
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">

                <div class="hidden lg:block absolute top-12 left-20 right-20 h-0.5 bg-slate-200 z-0"></div>

                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-16 h-16 bg-[#003399] rounded-2xl flex items-center justify-center text-[#FFB800] text-xl font-black shadow-lg mb-6 rotate-3 group-hover:rotate-0 transition-all duration-300"
                        style="clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);">
                        01
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Masuk ke Sistem</h4>
                    <p class="text-[11px] text-slate-500 max-w-[200px] leading-relaxed">Gunakan akun yang telah diberikan sekolah untuk mengakses e-Rapor.</p>
                </div>

                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-16 h-16 bg-[#003399] rounded-2xl flex items-center justify-center text-[#FFB800] text-xl font-black shadow-lg mb-6 rotate-3 group-hover:rotate-0 transition-all duration-300"
                        style="clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);">
                        02
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Lihat Hasil Belajar</h4>
                    <p class="text-[11px] text-slate-500 max-w-[200px] leading-relaxed">Periksa nilai, kehadiran, dan perkembangan belajar siswa dengan mudah.</p>
                </div>

                <div class="relative z-10 flex flex-col items-center text-center group">
                    <div class="w-16 h-16 bg-[#003399] rounded-2xl flex items-center justify-center text-[#FFB800] text-xl font-black shadow-lg mb-6 rotate-3 group-hover:rotate-0 transition-all duration-300"
                        style="clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);">
                        03
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Akses Rapor Digital</h4>
                    <p class="text-[11px] text-slate-500 max-w-[200px] leading-relaxed">Rapor dapat dilihat secara online dan diunduh kapan saja.</p>
                </div>
            </div>
        </section>
    </main>

    <section class="w-full py-20 bg-primary">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10 bg-white/5 p-8 lg:p-12 rounded-3xl border border-white/10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-secondary"></div>

                <div class="text-center lg:text-left z-10">
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-2">
                        Butuh Bantuan Teknis?
                    </h3>
                    <p class="text-white/70 text-xs max-w-xl leading-relaxed">
                        Jika Anda mengalami kendala saat melakukan pencarian NISN atau dokumen rapor tidak muncul, tim IT SMK TI Airlangga siap membantu Anda melalui layanan pesan instan.
                    </p>
                </div>

                <div class="flex shrink-0 z-10">
                    <a href="#" class="bg-secondary hover:bg-yellow-400 text-primary font-black text-xs uppercase tracking-widest px-10 py-5 rounded-2xl transition-all shadow-lg hover:shadow-yellow-500/20 flex items-center justify-center gap-3">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Hubungi Admin IT
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="max-w-4xl mx-auto px-6 lg:px-12 w-full my-20">
        <header class="max-w-4xl mx-auto px-6 lg:px-12 w-full mb-20">
            <div class="text-center mb-12">
                <h3 class="text-2xl font-black text-primary uppercase tracking-tight mb-4">
                    Pertanyaan <span class="text-secondary">Sering Diajukan</span>
                </h3>
                <div class="flex items-center justify-center gap-2">
                    <p class="text-xs text-slate-500 font-medium">
                        Temukan jawaban cepat terkait kendala teknis dan sistem e-Rapor sekolah.
                    </p>
                </div>
            </div>

            <div x-data="{ active: null }" class="space-y-4">
                </div>
        </header>

        <div x-data="{ active: null }" class="space-y-4">
            @php
                $faqs = [
                    ['q' => 'Mengapa saya tidak bisa login ke e-rapor?', 'a' => 'Biasanya terjadi karena NISN atau data siswa belum sesuai. Pastikan data benar, atau hubungi wali kelas/operator sekolah untuk reset akses.'],
                    ['q' => 'Mengapa nilai anak saya belum muncul?', 'a' => 'Nilai bisa belum tampil karena guru belum menyelesaikan input atau sekolah masih dalam proses finalisasi rapor semester.'],
                    ['q' => 'Apakah data nilai di e-rapor ini resmi?', 'a' => 'Ya. Semua data berasal dari sistem akademik sekolah dan telah melalui proses validasi oleh guru dan wali kelas sebelum dipublikasikan.'],
                    ['q' => 'Bagaimana cara mengunduh atau mencetak rapor?', 'a' => 'Gunakan tombol unduh PDF pada halaman rapor. File sudah diformat resmi dan dapat langsung dicetak.'],
                    ['q' => 'Apa yang harus dilakukan jika ada kesalahan pada nilai atau data?', 'a' => 'Segera hubungi wali kelas agar dilakukan pengecekan dan perbaikan oleh pihak sekolah sebelum data difinalisasi.']
                ];
            @endphp

            @foreach($faqs as $index => $item)
            <div class="bg-white border-l-4 border-primary rounded-xl shadow-sm overflow-hidden">
                <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none">
                    <span class="font-bold text-slate-800 text-xs">{{ $item['q'] }}</span>
                    <span class="text-secondary transition-transform duration-200"
                        :class="active === {{ $index }} ? 'rotate-180' : ''">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </button>

                <div x-show="active === {{ $index }}"
                    class="px-5 pb-5 text-[11px] text-slate-500 border-t border-slate-50 pt-3">
                    {{ $item['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <footer class="bg-[#003399] text-white py-16 mt-20 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-[#FFB800]"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-[#FFB800] rounded-full blur-[100px] opacity-10"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                <div class="lg:col-span-5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center justify-center text-[#003399]">
                            <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="" class="w-10 h-10">
                        </div>
                        <div>
                            <h2 class="text-lg font-black uppercase tracking-tight">e-Rapor</h2>
                            <p class="text-[10px] text-[#FFB800] font-bold uppercase tracking-widest">SMK TI Airlangga</p>
                        </div>
                    </div>
                    <p class="text-white/60 text-xs leading-relaxed max-w-sm mb-8">
                       E-Rapor digital SMK TI Airlangga Samarinda untuk melihat nilai siswa secara transparan, akurat, dan mudah diakses oleh orang tua, guru, dan siswa.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#FFB800] hover:text-[#003399] transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#FFB800] hover:text-[#003399] transition-all"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#FFB800] hover:text-[#003399] transition-all"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <h4 class="text-[11px] font-black uppercase text-[#FFB800] tracking-widest mb-6">Navigasi Sistem</h4>
                    <ul class="space-y-4 text-xs text-white/70">
                        <li><a href="#" class="hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Unggulan</a></li>
                        <li><a href="#alur" class="hover:text-white transition-colors">Alur Penggunaan</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">FAQ & Bantuan</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-[#002B80] p-6 rounded-2xl border border-white/5 shadow-xl">
                        <h4 class="text-[11px] font-black uppercase text-[#FFB800] tracking-widest mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-clock"></i> Jam Operasional
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs border-b border-white/5 pb-2">
                                <span class="text-white/60">Senin - Jumat</span>
                                <span class="font-bold">07:30 - 16:00</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-white/60">Sabtu</span>
                                <span class="font-bold">Layanan Terbatas</span>
                            </div>
                        </div>
                        <p class="text-[9px] text-white/40 mt-6 italic">*Layanan teknis mengikuti jam kerja kurikulum sekolah.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] text-white/40 font-mono uppercase tracking-widest">
                    &copy; {{ date('Y') }} SMK TI AIRLANGGA SAMARINDA. ALL RIGHTS RESERVED.
                </p>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const targetDate = new Date("June 20, 2026 08:00:00").getTime();

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance < 0) {
                clearInterval(timer);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("cd-days").innerText = days.toString().padStart(2, '0');
            document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("cd-minutes").innerText = minutes.toString().padStart(2, '0');
            document.getElementById("cd-seconds").innerText = seconds.toString().padStart(2, '0');
        }, 1000);
    });
</script>
@endsection
