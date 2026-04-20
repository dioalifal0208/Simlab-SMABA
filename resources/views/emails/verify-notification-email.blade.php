@extends('emails.layout')

@section('title', 'Verifikasi Email Notifikasi')

@section('badge')
<table role="presentation" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="background-color: #dbeafe; border: 1px solid #bfdbfe; border-radius: 20px; padding: 6px 14px;">
            <span style="font-size: 12px; font-weight: 600; color: #1e40af;">📧 Verifikasi Email</span>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h2 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #1e3a8a;">Verifikasi Email Notifikasi</h2>

    <p style="margin: 0 0 16px 0; color: #4b5563;">
        Halo <strong>{{ $user->name }}</strong>,
    </p>

    <p style="margin: 0 0 20px 0; color: #4b5563;">
        Anda telah mendaftarkan <strong>{{ $user->notification_email }}</strong> sebagai email notifikasi di Lab SMABA.
        Klik tombol di bawah untuk memverifikasi alamat email ini.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td align="center" style="padding: 8px 0 24px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="background-color: #1e3a8a; border-radius: 8px;">
                            <a href="{{ $verificationUrl }}"
                               target="_blank"
                               style="display: inline-block; padding: 14px 36px; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; letter-spacing: 0.3px;">
                                ✅ Verifikasi Email Saya
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Info Box --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="background-color: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 0 6px 6px 0; padding: 14px 16px;">
                <p style="margin: 0 0 6px 0; font-size: 13px; font-weight: 600; color: #166534;">Setelah diverifikasi, Anda akan menerima:</p>
                <p style="margin: 0; font-size: 13px; color: #15803d; line-height: 1.6;">
                    • Notifikasi peminjaman alat (disetujui/ditolak)<br>
                    • Pengingat pengembalian (H-1 jatuh tempo)<br>
                    • Pemberitahuan jika terlambat mengembalikan<br>
                    • Notifikasi booking laboratorium
                </p>
            </td>
        </tr>
    </table>

    <p style="margin: 24px 0 8px 0; font-size: 13px; color: #9ca3af;">
        Link ini berlaku selama <strong>60 menit</strong>.
    </p>

    <p style="margin: 0; font-size: 12px; color: #d1d5db;">
        Jika tombol tidak berfungsi, salin URL ini ke browser:<br>
        <span style="color: #9ca3af; word-break: break-all;">{{ $verificationUrl }}</span>
    </p>
@endsection
