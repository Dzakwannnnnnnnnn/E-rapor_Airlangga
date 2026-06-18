@extends('layouts.app')

@section('title', 'Aktivasi Akun - e-Rapor')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] flex flex-col relative font-sans selection:bg-[#003399]/25 selection:text-[#003399]">

    <div class="relative bg-[#003399] pt-10 pb-20 overflow-hidden z-0">
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-10"></div>
        <div class="absolute bottom-12 right-6 opacity-20 grid grid-cols-4 gap-1.5 pointer-events-none z-10">
            @for ($i = 0; $i < 12; $i++)
                <div class="w-1 h-1 bg-[#FFB800] rounded-full"></div>
            @endfor
        </div>
        <div class="absolute top-10 right-[10%] w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none z-0"></div>

        <a href="/" class="absolute top-4 left-6 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-20 transition-all backdrop-blur-md">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>

        <div class="px-6 relative z-20 mt-8 max-w-md mx-auto">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-white p-2 rounded-xl shadow-md border-b-2 border-[#FFB800]">
                    <img src="{{ asset('SMKTI Airlangga Samarinda Icon.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <h1 class="text-lg font-black text-white tracking-tight uppercase leading-none">e-Rapor</h1>
                    <p class="text-[9px] text-[#FFB800] font-bold uppercase tracking-widest mt-0.5">SMK TI Airlangga</p>
                </div>
            </div>

            <h2 class="text-3xl font-black text-white tracking-tight leading-none mb-2 uppercase">
                Aktivasi <br>
                <span class="text-[#FFB800]">Kata Sandi</span>
            </h2>
            <p class="text-[11px] text-white/70 max-w-[280px] font-normal leading-relaxed">
                Halo, <strong class="text-white font-semibold">{{ $user->name }}</strong>! Selamat datang. Silakan buat kata sandi baru untuk mengaktifkan akun Anda.
            </p>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-8 bg-[#FFB800] z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-6 bg-[#F4F6F9] z-10" style="clip-path: polygon(0 100%, 100% 0, 100% 100%, 0 100%);"></div>
    </div>

    <div class="flex-1 px-6 pt-6 w-full max-w-md mx-auto relative z-10 pb-28 lg:pb-12">

        <div class="bg-white border border-slate-200 rounded-xl p-3.5 mb-5 flex items-center gap-3 shadow-sm select-none">
            <div class="w-9 h-9 rounded-full bg-[#003399]/10 text-[#003399] flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user text-sm"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-slate-900 text-xs font-black truncate leading-tight">{{ $user->name }}</p>
                <p class="text-slate-500 text-[11px] truncate mt-0.5 leading-none">{{ $user->email }}</p>
            </div>
            <span class="shrink-0 text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md
                {{ $user->role === 'teacher' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                {{ $user->role === 'teacher' ? 'Guru' : 'Wali Murid' }}
            </span>
        </div>

        @if ($errors->any())
            <x-alert type="error" :message="implode(' ', $errors->all())" class="mb-5" />
        @endif

        <form method="POST" action="{{ route('account.activate') }}" id="activateForm" class="flex flex-col">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-5 relative group">
                <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Kata Sandi Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#003399] transition-colors">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password" oninput="checkStrength(this.value)" placeholder="Minimal 8 karakter" maxlength="16"
                        class="w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:border-[#003399] focus:ring-2 focus:ring-[#003399]/20 transition-all outline-none shadow-sm">
                    <button type="button" onclick="togglePw('password', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                        <i class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>

                <div class="flex gap-1 mt-2.5 mx-1" id="strengthBar">
                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s1"></div>
                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s2"></div>
                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s3"></div>
                    <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s4"></div>
                </div>
                <p class="text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1" id="strengthLabel"></p>
            </div>

            <div class="mb-6 relative group">
                <label for="password_confirmation" class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#003399] transition-colors">
                        <i class="fa-solid fa-lock-open text-sm"></i>
                    </div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required oninput="checkMatch()" placeholder="Ulangi kata sandi Anda" maxlength="16"
                        class="w-full pl-11 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:border-[#003399] focus:ring-2 focus:ring-[#003399]/20 transition-all outline-none shadow-sm">
                    <button type="button" onclick="togglePw('password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                        <i class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1 hidden" id="matchLabel"></p>
            </div>

            <button type="submit" class="hidden lg:flex w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 items-center justify-center gap-2 border-b-2 border-slate-900/20 cursor-pointer">
                <i class="fa-solid fa-shield-check text-[#FFB800]"></i>
                Aktifkan Akun Sekarang
            </button>
        </form>

        <p class="text-center text-slate-400 text-[11px] font-medium mt-8 leading-relaxed select-none">
            Tautan aktivasi ini berlaku selama <span class="text-slate-500 font-bold">24 jam</span> sejak pengiriman email dari sistem.
        </p>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 pb-6 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)] z-50">
        <div class="max-w-md mx-auto">
            <button type="submit" form="activateForm" class="w-full bg-[#003399] hover:bg-[#002266] text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl transition-all shadow-lg shadow-[#003399]/20 flex items-center justify-center gap-2 border-b-2 border-slate-900/20 cursor-pointer">
                <i class="fa-solid fa-shield-check text-[#FFB800]"></i>
                Aktifkan Akun Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    function togglePw(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function checkStrength(val) {
        const bars = ['s1', 's2', 's3', 's4'];
        const label = document.getElementById('strengthLabel');
        let score = 0;

        if (val.length > 0) {
            let hasLower = /[a-z]/.test(val) ? 1 : 0;
            let hasUpper = /[A-Z]/.test(val) ? 1 : 0;
            let hasNumber = /[0-9]/.test(val) ? 1 : 0;
            let hasSymbol = /[^A-Za-z0-9]/.test(val) ? 1 : 0;

            let criteriaMet = hasLower + hasUpper + hasNumber + hasSymbol;

            if (val.length < 8) {
                score = 1;
            } else {
                score = criteriaMet;
            }
        }

        const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500'];
        const labels = ['Sangat Lemah', 'Lemah', 'Kuat', 'Sangat Kuat'];
        const texts  = ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-emerald-600'];

        bars.forEach((id, i) => {
            const el = document.getElementById(id);
            if (el) {
                el.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' +
                    (i < score ? colors[score - 1] : 'bg-slate-200');
            }
        });

        if (val.length > 0) {
            let warning = '';
            if (val.length < 8) {
                warning = ' (Minimal 8 karakter)';
            }
            label.textContent = labels[score - 1] + warning;
            label.className = 'text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1 ' + (texts[score - 1] || 'text-red-500');
        } else {
            label.textContent = '';
        }
        checkMatch();
    }

    function checkMatch() {
        const pw = document.getElementById('password').value;
        const conf = document.getElementById('password_confirmation').value;
        const lbl = document.getElementById('matchLabel');

        if (conf.length === 0) {
            lbl.classList.add('hidden');
            return;
        }

        lbl.classList.remove('hidden');
        if (pw === conf) {
            lbl.textContent = '✓ Kata sandi cocok';
            lbl.className = 'text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1 text-emerald-600';
        } else {
            lbl.textContent = '✗ Kata sandi tidak cocok';
            lbl.className = 'text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1 text-red-500';
        }
    }
</script>
@endsection
