<x-guest-layout>
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg" data-aos="fade-up">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-smaba-text">Verifikasi Google Authenticator</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan kode 6 digit dari aplikasi authenticator atau recovery code.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-3 text-sm rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 text-red-700 p-3 text-sm rounded">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700">Kode</label>
                <input id="otp" name="code" inputmode="numeric" maxlength="16" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue tracking-widest text-center text-lg">
                <p class="text-xs text-gray-500 mt-1">Bisa pakai TOTP 6 digit atau recovery code.</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow-md hover:bg-smaba-light-blue transition-colors">
                    Verifikasi & Masuk
                </button>
            </div>
        </form>

        <div class="mt-4 flex items-center justify-end">
            <a href="{{ route('login.create') }}" class="text-xs text-gray-500 hover:text-gray-700">Ganti akun</a>
        </div>
    </div>
</x-guest-layout>
