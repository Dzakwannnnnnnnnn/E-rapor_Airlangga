@extends('layouts.dashboard')

@section('title', 'Ubah Password - e-Rapor')
@section('back_url', route('profile.edit'))
@section('hide_bottom_nav', true)

@section('content')
<!-- Breakout container for full width hero banner -->
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">
    
    <!-- Hero Banner (Full width) -->
    <div class="bg-primary text-white pt-10 pb-16 px-6 md:px-8 relative overflow-hidden select-none">
        
        <!-- Background Decorations (referensi welcome.blade.php) -->
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>
        
        <!-- Back Button & Banner Content -->
        <div class="max-w-3xl mx-auto flex items-center gap-4 relative z-10">
            <a href="{{ route('profile.edit') }}" 
               class="w-10 h-10 rounded-full bg-white/10 border border-white/25 hover:bg-white/20 flex items-center justify-center text-white transition-colors shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            
            <div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-none uppercase tracking-wide">Ubah Password</h3>
                <p class="text-xs md:text-sm text-white/80 font-medium mt-1">Pastikan akun Anda menggunakan kata sandi yang aman</p>
            </div>
        </div>

        <!-- Slanted Bottom Lines (referensi welcome.blade.php) -->
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10"
             style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);">
        </div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10"
             style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);">
        </div>
    </div>
</div>

<!-- Form Container (Centered and padded for mobile bottom bar) -->
<div class="max-w-xl mx-auto px-4 md:px-0 mt-8 pb-24 md:pb-6 relative z-20">

    <!-- Status Alerts -->
    @if (session('status') === 'password-updated')
        <x-alert type="success" message="Password Akun Berhasil Diperbarui" class="mb-6" />
    @endif

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Password Saat Ini Card -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-key text-sm"></i>
                </div>
                <label for="update_password_current_password" class="font-extrabold text-xs text-slate-500 uppercase tracking-widest">Password Saat Ini</label>
            </div>
            <div class="relative">
                <input type="password" name="current_password" id="update_password_current_password" autocomplete="current-password" required maxlength="16"
                       class="w-full text-center font-extrabold text-slate-800 bg-white border-2 border-slate-200 focus:border-primary focus:ring-primary rounded-2xl py-3 px-12 shadow-sm text-base transition-colors duration-150">
                <button type="button" onclick="togglePw('update_password_current_password', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <!-- Password Baru Card -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <label for="update_password_password" class="font-extrabold text-xs text-slate-500 uppercase tracking-widest">Password Baru</label>
            </div>
            <div class="relative">
                <input type="password" name="password" id="update_password_password" autocomplete="new-password" required maxlength="16" oninput="checkStrength(this.value)"
                       class="w-full text-center font-extrabold text-slate-800 bg-white border-2 border-slate-200 focus:border-primary focus:ring-primary rounded-2xl py-3 px-12 shadow-sm text-base transition-colors duration-150">
                <button type="button" onclick="togglePw('update_password_password', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <div class="flex gap-1 mt-2.5 mx-1" id="strengthBar">
                <div class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s1"></div>
                <div class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s2"></div>
                <div class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s3"></div>
                <div class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300" id="s4"></div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1" id="strengthLabel"></p>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <!-- Konfirmasi Password Baru Card -->
        <div class="space-y-2.5">
            <div class="flex items-center gap-3 pl-1">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <label for="update_password_password_confirmation" class="font-extrabold text-xs text-slate-500 uppercase tracking-widest">Konfirmasi Password Baru</label>
            </div>
            <div class="relative">
                <input type="password" name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password" required maxlength="16" oninput="checkMatch()"
                       class="w-full text-center font-extrabold text-slate-800 bg-white border-2 border-slate-200 focus:border-primary focus:ring-primary rounded-2xl py-3 px-12 shadow-sm text-base transition-colors duration-150">
                <button type="button" onclick="togglePw('update_password_password_confirmation', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <p class="text-[10px] font-black uppercase tracking-wider mt-1.5 ml-1 hidden" id="matchLabel"></p>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Floating Bottom Save Button Bar on Mobile, Normal Inline Button on Desktop -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-100 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] md:shadow-none rounded-t-3xl md:rounded-none z-40 md:static md:bg-transparent md:border-0 md:p-0 md:mt-8 flex justify-center md:justify-start">
            <button type="submit" class="w-full md:w-auto px-10 py-3.5 bg-primary hover:bg-primary/95 text-white font-extrabold text-xs uppercase tracking-widest rounded-2xl md:rounded-xl shadow-md transition-all">
                Ubah Password
            </button>
        </div>
    </form>
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
                el.className = 'h-1.5 flex-1 rounded-full transition-all duration-300 ' +
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
        const pw = document.getElementById('update_password_password').value;
        const conf = document.getElementById('update_password_password_confirmation').value;
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
