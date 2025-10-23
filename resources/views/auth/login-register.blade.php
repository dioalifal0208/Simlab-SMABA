<x-guest-layout>
    <div x-data="{ mode: '{{ $mode }}' }">
        {{-- FORM LOGIN --}}
        <div x-show="mode === 'login'" x-transition>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div><x-input-label for="email" value="Email" /><x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus /></div>
                <div class="mt-4"><x-input-label for="password" value="Password" /><x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required/></div>
                <div class="flex items-center justify-end mt-4"><x-primary-button>{{ __('Log In') }}</x-primary-button></div>
            </form>
            <div class="mt-4 text-center text-sm">Belum punya akun? <button @click="mode = 'register'" class="underline text-indigo-600">Daftar di sini</button></div>
        </div>

        {{-- FORM REGISTER --}}
        <div x-show="mode === 'register'" x-transition style="display: none;">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div><x-input-label for="name" value="Name" /><x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus /></div>
                <div class="mt-4"><x-input-label for="register_email" value="Email" /><x-text-input id="register_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required /></div>
                <div class="mt-4"><x-input-label for="register_password" value="Password" /><x-text-input id="register_password" class="block mt-1 w-full" type="password" name="password" required /></div>
                <div class="mt-4"><x-input-label for="password_confirmation" value="Confirm Password" /><x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required /></div>
                <div class="flex items-center justify-end mt-4"><x-primary-button>{{ __('Register') }}</x-primary-button></div>
            </form>
            <div class="mt-4 text-center text-sm">Sudah punya akun? <button @click="mode = 'login'" class="underline text-indigo-600">Login di sini</button></div>
        </div>
    </div>
</x-guest-layout>