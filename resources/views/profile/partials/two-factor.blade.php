@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    $user = auth()->user();
    $setupSecret = session('2fa:setup:secret');
    $appName = config('app.name', 'Lab SMABA');
    $issuer = urlencode($appName);
    $email = $user?->email ?? '';
    $otpauth = $setupSecret
        ? "otpauth://totp/{$issuer}:{$email}?secret={$setupSecret}&issuer={$issuer}&digits=6&period=30"
        : null;
    $qr = $otpauth ? base64_encode(QrCode::format('svg')->size(240)->generate($otpauth)) : null;
    $recoveryPlain = session('two_factor_recovery_plain', []);
@endphp

<div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
    <div class="max-w-xl space-y-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Keamanan Akun (Google Authenticator)</h3>
            <p class="text-sm text-gray-600">Aktifkan 2FA berbasis TOTP. Scan QR, lalu masukkan kode 6 digit untuk mengonfirmasi.</p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 text-sm rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-3 text-sm rounded space-y-1">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (!empty($recoveryPlain))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm">
                <p class="font-semibold text-blue-800 mb-2">Recovery Codes (simpan di tempat aman)</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-blue-900">
                    @foreach ($recoveryPlain as $code)
                        <span class="px-2 py-1 bg-white rounded border border-blue-100">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-700">Status</p>
                <p class="text-sm {{ $user->two_factor_enabled ? 'text-green-600' : 'text-red-500' }}">
                    {{ $user->two_factor_enabled ? 'Aktif' : 'Tidak aktif' }}
                    @if ($setupSecret && !$user->two_factor_enabled)
                        <span class="text-xs text-amber-600 ml-1">(Draft QR menunggu konfirmasi)</span>
                    @endif
                </p>
            </div>
            @if ($user->two_factor_enabled || $setupSecret)
                <form action="{{ route('two-factor.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-semibold">
                        {{ $user->two_factor_enabled ? 'Matikan 2FA' : 'Batalkan setup' }}
                    </button>
                </form>
            @endif
        </div>

        @if (!$setupSecret && !$user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.start') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    Mulai aktifkan (buat QR)
                </button>
            </form>
        @endif

        @if ($setupSecret)
            <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                <p class="text-sm text-gray-700 font-semibold">Langkah aktivasi</p>
                <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1">
                    <li>Buka Google/Microsoft Authenticator.</li>
                    <li>Scan QR di bawah atau masukkan secret manual.</li>
                    <li>Masukkan kode 6 digit lalu klik konfirmasi.</li>
                </ol>
                @if ($qr)
                    <div class="flex items-center justify-center py-2">
                        <img src="data:image/svg+xml;base64,{{ $qr }}" alt="QR Code" class="w-48 h-48">
                    </div>
                @endif
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded p-2 text-center text-sm">
                    Secret: <span class="font-mono">{{ $setupSecret }}</span>
                </div>
                <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-2">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700" for="code">Kode 6 digit</label>
                    <input id="code" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue">
                    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        Konfirmasi & Aktifkan
                    </button>
                </form>
            </div>
        @endif

        @if ($user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.recovery') }}" class="space-y-2">
                @csrf
                <p class="text-sm text-gray-700 font-semibold">Recovery Codes</p>
                <p class="text-xs text-gray-500">Gunakan ketika kehilangan akses ke aplikasi authenticator. Unduh/salin dan simpan aman.</p>
                <button type="submit" class="px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    Buat ulang recovery codes
                </button>
            </form>
        @endif
    </div>
</div>
