<x-guest-layout>
    {{-- FORM LOGIN ONLY --}}
    <div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div><x-input-label for="email" value="Email" /><x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus /></div>
            <div class="mt-4"><x-input-label for="password" value="Password" /><x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required/></div>
            <div class="flex items-center justify-end mt-4"><x-primary-button>{{ __('Log In') }}</x-primary-button></div>
        </form>
    </div>
</x-guest-layout>