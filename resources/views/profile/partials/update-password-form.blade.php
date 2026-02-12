<section class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="100" data-aos-once="true">
    <div class="p-6 md:p-8 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-smaba-dark-blue uppercase tracking-wide">Keamanan</p>
                <h3 class="text-xl font-bold text-smaba-text">Ubah Kata Sandi</h3>
                <p class="text-sm text-gray-500">Gunakan kata sandi unik dengan kombinasi huruf, angka, dan simbol untuk menjaga akun tetap aman.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                Tips: perbarui berkala
            </span>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">{{ __('Current Password') }}</label>
                    <x-text-input
                        id="update_password_current_password"
                        name="current_password"
                        type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-gray-700">{{ __('New Password') }}</label>
                    <x-text-input
                        id="update_password_password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
                    <x-text-input
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <button type="submit" class="px-5 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    {{ __('Save') }}
                </button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>
