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
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">{{ __('profile.sections.two_factor.badge') }}</p>
                <h3 class="text-xl font-bold text-gray-900">{{ __('profile.sections.two_factor.title') }}</h3>
                <p class="text-sm text-gray-500">{{ __('profile.sections.two_factor.subtitle') }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold @if($twoFactorStatus === 'active') bg-green-100 text-green-700 @elseif($twoFactorStatus === 'pending') bg-amber-100 text-amber-700 @else bg-gray-100 text-gray-600 @endif">
                <span class="w-2 h-2 mr-2 rounded-full @if($twoFactorStatus === 'active') bg-green-600 @elseif($twoFactorStatus === 'pending') bg-amber-500 @else bg-gray-400 @endif"></span>
                @if($twoFactorStatus === 'active')
                    {{ __('profile.sections.two_factor.status_active') }}
                @elseif($twoFactorStatus === 'pending')
                    {{ __('profile.sections.two_factor.status_pending') }}
                @else
                    {{ __('profile.sections.two_factor.status_inactive') }}
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
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-sm">
                <p class="font-semibold text-green-800 mb-2">{{ __('profile.messages.recovery_codes_title') }}</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-xs text-green-900">
                    @foreach ($recoveryPlain as $code)
                        <span class="px-2 py-1 bg-white rounded border border-green-100">{{ $code }}</span>
                    @endforeach
                </div>
                <p class="mt-3 text-green-700">{{ __('profile.messages.recovery_codes_desc') }}</p>
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-gray-600">
                <p class="font-semibold text-gray-800">
                    {{ __('profile.labels.two_factor_status', ['status' => $user->two_factor_enabled ? __('profile.labels.two_factor_active') : __('profile.labels.two_factor_inactive')]) }}
                </p>
                @if ($setupSecret && !$user->two_factor_enabled)
                    <p class="text-amber-700">{{ __('profile.messages.two_factor_pending') }}</p>
                @elseif(! $user->two_factor_enabled)
                    <p class="text-gray-500">{{ __('profile.messages.two_factor_suggestion') }}</p>
                @endif
            </div>

            @if ($user->two_factor_enabled || $setupSecret)
                <form action="{{ route('two-factor.disable') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">
                        {{ $user->two_factor_enabled ? __('profile.buttons.disable_2fa') : __('profile.buttons.cancel_setup') }}
                    </button>
                </form>
            @endif
        </div>

        @if (!$setupSecret && ! $user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.start') }}" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4">
                @csrf
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-gray-800">{{ __('profile.messages.two_factor_qr_instruction') }}</p>
                    <p class="text-gray-500">{{ __('profile.messages.two_factor_qr_subtitle') }}</p>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-500 transition-colors">
                    {{ __('profile.buttons.start') }}
                </button>
            </form>
        @endif

        @if ($setupSecret)
            <div class="border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
                <p class="text-sm font-semibold text-gray-800">{{ __('profile.messages.two_factor_steps_title') }}</p>
                <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1">
                    <li>{{ __('profile.messages.two_factor_steps.step1') }}</li>
                    <li>{{ __('profile.messages.two_factor_steps.step2') }}</li>
                    <li>{{ __('profile.messages.two_factor_steps.step3') }}</li>
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
                    <label class="block text-sm font-medium text-gray-700" for="code">{{ __('profile.labels.code_6_digit') }}</label>
                    <input
                        id="code"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600"
                    >
                    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors">
                        {{ __('profile.buttons.confirm_activate') }}
                    </button>
                </form>
            </div>
        @endif

        @if ($user->two_factor_enabled)
            <form method="POST" action="{{ route('two-factor.recovery') }}" class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4">
                @csrf
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ __('profile.messages.recovery_codes_label') }}</p>
                    <p class="text-xs text-gray-500">{{ __('profile.messages.recovery_codes_usage') }}</p>
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-indigo-500 transition-colors">
                    {{ __('profile.buttons.regenerate_recovery') }}
                </button>
            </form>
        @endif
    </div>
</section>

