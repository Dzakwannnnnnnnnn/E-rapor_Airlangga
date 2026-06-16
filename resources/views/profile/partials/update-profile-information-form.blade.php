<section class="text-left">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold text-gray-700 uppercase tracking-wider" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="text-xs font-bold text-gray-700 uppercase tracking-wider" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs text-yellow-800 leading-normal">
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                        <button form="send-verification" class="underline font-bold text-yellow-900 hover:text-yellow-950 block mt-1">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-bold text-xs text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg shadow-sm transition-colors duration-150">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-green-600 font-bold flex items-center gap-1"
                >
                    <i class="fa-solid fa-circle-check"></i> {{ __('Tersimpan.') }}
                </span>
            @endif
        </div>
    </form>
</section>
