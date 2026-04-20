@extends('emails.layout')

@section('title', 'Kode OTP Login')

@section('badge')
<table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 20px; padding: 6px 14px;">
            <span style="font-size: 12px; font-weight: 600; color: #92400e;">🔐 Keamanan Akun</span>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #1e3a8a;">Kode OTP Login</h2>

    <p style="margin: 0 0 20px 0; color: #4b5563;">
        Halo <strong>{{ $name }}</strong>, gunakan kode berikut untuk menyelesaikan login ke Lab SMABA.
    </p>

    {{-- OTP Code Box --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 24px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="background-color: #eff6ff; border: 2px dashed #3b82f6; border-radius: 12px; padding: 20px 40px; text-align: center;">
                            <span style="font-size: 36px; letter-spacing: 8px; font-weight: 800; color: #1e3a8a; font-family: 'Courier New', monospace;">{{ $code }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Info Box --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="background-color: #fefce8; border-left: 4px solid #eab308; border-radius: 0 6px 6px 0; padding: 14px 16px;">
                <p style="margin: 0; font-size: 13px; color: #713f12;">
                    ⏱️ Kode ini berlaku selama <strong>{{ $expiresMinutes }} menit</strong>. Jangan bagikan kode ini kepada siapapun.
                </p>
            </td>
        </tr>
    </table>

    <p style="margin: 24px 0 0 0; font-size: 13px; color: #9ca3af;">
        Jika Anda tidak mencoba masuk, abaikan email ini. Akun Anda tetap aman.
    </p>
@endsection
