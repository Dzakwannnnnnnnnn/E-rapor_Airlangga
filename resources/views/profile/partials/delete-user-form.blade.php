<section class="space-y-6">
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-left">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-950">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                {{ __('Setelah akun Anda dihapus, semua data dan sumber daya di dalamnya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full border-gray-300 focus:border-danger focus:ring-danger rounded-lg"
                    placeholder="{{ __('Masukkan Password Anda') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-lg">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="rounded-lg bg-danger hover:bg-danger/90">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
