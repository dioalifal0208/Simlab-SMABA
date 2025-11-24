@php
    $requiresEmailVerification = $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail;
    $emailVerified = $requiresEmailVerification ? $user->hasVerifiedEmail() : true;
@endphp

<section class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-once="true">
    <div class="p-6 md:p-8 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-smaba-dark-blue uppercase tracking-wide">Data diri</p>
                <h3 class="text-xl font-bold text-smaba-text">Informasi Profil</h3>
                <p class="text-sm text-gray-500">Perbarui nama dan email agar notifikasi laboratorium tetap Anda terima.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $emailVerified ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                <span class="w-2 h-2 mr-2 rounded-full {{ $emailVerified ? 'bg-green-600' : 'bg-amber-500' }}"></span>
                {{ $emailVerified ? 'Email terverifikasi' : 'Email belum terverifikasi' }}
            </span>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                        :value="old('name', $user->name)"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div class="md:col-span-1">
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                        :value="old('email', $user->email)"
                        required
                        autocomplete="username"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($requiresEmailVerification && ! $emailVerified)
                        <div class="mt-3 flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 p-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-200 text-amber-700 text-xs font-bold">!</span>
                            <div class="text-sm text-amber-800 space-y-1">
                                <p>Email Anda belum diverifikasi.</p>
                                <button form="send-verification" class="text-sm font-semibold text-smaba-dark-blue hover:text-smaba-light-blue underline">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-3 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <button type="submit" class="px-5 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    {{ __('Save') }}
                </button>

                @if (session('status') === 'profile-updated')
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
