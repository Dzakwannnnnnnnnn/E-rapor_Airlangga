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
                <x-input-label for="password_delete" value="{{ __('Password') }}" class="sr-only" />

                <div class="relative">
                    <x-text-input
                        id="password_delete"
                        name="password"
                        type="password"
                        maxlength="16"
                        class="mt-1 block w-full pr-12 border-gray-300 focus:border-danger focus:ring-danger rounded-lg"
                        placeholder="{{ __('Masukkan Password Anda') }}"
                    />
                    <button type="button" onclick="toggleDeletePw('password_delete', this)"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                        <i class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>

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
        <script>
            function toggleDeletePw(fieldId, btn) {
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
        </script>
    </x-modal>
</section>
