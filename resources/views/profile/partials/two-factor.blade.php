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
    $twoFactorStatus = $user->two_factor_enabled ? 'active' : ($setupSecret ? 'pending' : 'inactive');
@endphp

<section class="bg-white overflow-hidden shadow-lg sm:rounded-xl" data-aos="fade-up" data-aos-delay="200" data-aos-once="true">
    <div class="p-6 md:p-8 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold text-smaba-dark-blue uppercase tracking-wide">Keamanan lanjutan</p>
                <h3 class="text-xl font-bold text-smaba-text">Autentikasi Dua Faktor (TOTP)</h3>
                <p class="text-sm text-gray-500">Tambahkan lapisan keamanan dengan aplikasi Google/Microsoft Authenticator.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold @if($twoFactorStatus === 'active') bg-green-100 text-green-700 @elseif($twoFactorStatus === 'pending') bg-amber-100 text-amber-700 @else bg-gray-100 text-gray-600 @endif">
                <span class="w-2 h-2 mr-2 rounded-full @if($twoFactorStatus === 'active') bg-green-600 @elseif($twoFactorStatus === 'pending') bg-amber-500 @else bg-gray-400 @endif"></span>
                @if($twoFactorStatus === 'active')
                    2FA aktif
                @elseif($twoFactorStatus === 'pending')
                    Menunggu konfirmasi
                @else
                    Belum diaktifkan
                @endif
            </span>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (!empty($recoveryPlain))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm">
                <p class="font-semibold text-blue-800 mb-2">Recovery codes baru</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-blue-900">
                    @foreach ($recoveryPlain as $code)
                        <span class="px-2 py-1 bg-white rounded border border-blue-100">{{ $code }}</span>
                    @endforeach
                </div>
                <p class="mt-3 text-blue-700">Simpan kode ini di tempat yang hanya Anda yang dapat mengaksesnya.</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-gray-600">
                <p class="font-semibold text-gray-800">
                    Status: {{ $user->two_factor_enabled ? 'Aktif dan terproteksi' : 'Belum aktif' }}
                </p>
                @if ($setupSecret && !$user->two_factor_enabled)
                    <p class="text-amber-700">QR sudah dibuat, selesaikan konfirmasi untuk mengaktifkan.</p>
                @elseif(! $user->two_factor_enabled)
                    <p class="text-gray-500">Kami sarankan mengaktifkan 2FA untuk semua akun pengelola.</p>
                @endif
            </div>

            @if ($user->two_factor_enabled || $setupSecret)
                <form action="{{ route('two-factor.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">
                        {{ $user->two_factor_enabled ? 'Matikan 2FA' : 'Batalkan setup' }}
                    </button>
                </form>
            @endif
        </div>

        @if (!$setupSecret && ! $user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.start') }}" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4">
                @csrf
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-gray-800">Aktifkan 2FA sekarang</p>
                    <p class="text-gray-500">Buat kode QR untuk dipindai di aplikasi authenticator.</p>
                </div>
                <button type="submit" class="px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    Mulai
                </button>
            </form>
        @endif

        @if ($setupSecret)
            <div class="border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
                <p class="text-sm font-semibold text-gray-800">Langkah aktivasi</p>
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

                <div class="bg-white border border-dashed border-gray-300 rounded p-2 text-center text-sm">
                    Secret: <span class="font-mono">{{ $setupSecret }}</span>
                </div>

                <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-2">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700" for="code">Kode 6 digit</label>
                    <input
                        id="code"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-smaba-dark-blue focus:ring-smaba-dark-blue"
                    >
                    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        Konfirmasi & Aktifkan
                    </button>
                </form>
            </div>
        @endif

        @if ($user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.recovery') }}" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4">
                @csrf
                <div>
                    <p class="text-sm font-semibold text-gray-800">Recovery codes</p>
                    <p class="text-xs text-gray-500">Gunakan saat kehilangan akses ke aplikasi authenticator.</p>
                </div>
                <button type="submit" class="px-4 py-2 bg-smaba-dark-blue text-white text-sm font-semibold rounded-lg shadow hover:bg-smaba-light-blue transition-colors">
                    Buat ulang
                </button>
            </form>
        @endif
    </div>
</section>
