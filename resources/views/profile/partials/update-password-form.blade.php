<section class="text-left">
    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-xs font-bold text-gray-700 uppercase tracking-wider" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-xs font-bold text-gray-700 uppercase tracking-wider" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-xs font-bold text-gray-700 uppercase tracking-wider" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-primary focus:ring-primary text-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg shadow-sm transition-colors duration-150">
                {{ __('Ubah Password') }}
            </button>

            @if (session('status') === 'password-updated')
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
