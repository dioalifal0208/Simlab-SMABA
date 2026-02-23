<section class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-red-100" data-aos="fade-up" data-aos-delay="250" data-aos-once="true">
    <div class="p-6 md:p-8 space-y-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">
                !
            </div>
            <div>
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Akun & data</p>
                <h3 class="text-xl font-bold text-smaba-text">Hapus Akun</h3>
                <p class="text-sm text-gray-600">Tindakan ini bersifat permanen. Pastikan Anda sudah mengunduh data penting sebelum melanjutkan.</p>
            </div>
        </div>

        <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                <li>Semua data dan riwayat akses akan dihapus.</li>
                <li>Tidak dapat dipulihkan setelah konfirmasi.</li>
                <li>Harus memasukkan kata sandi untuk menyetujui.</li>
            </ul>
        </div>

        <x-danger-button
            class="mt-1"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        ">Hapus Akun</x-danger-button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
                @csrf
                @method('delete')

                <div class="space-y-2">
                    <h2 class="text-lg font-semibold text-gray-900">Apakah Anda yakin ingin menghapus akun ini?</h2>
                    <p class="text-sm text-gray-600">Setelah akun dihapus, semua data dan riwayat terkait akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengkonfirmasi.</p>
                </div>

                <div>
                    <x-input-label for="password" value="Kata Sandi" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Masukkan kata sandi..."
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Batalkan
                    </x-secondary-button>

                    <x-danger-button>
                        Hapus Akun Saya
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</section>
