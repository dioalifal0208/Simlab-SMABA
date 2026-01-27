<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Login</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f7fafc; padding:24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;">
        <tr>
            <td style="padding:20px 24px;">
                <h2 style="margin:0 0 8px 0; color:#1a202c;">Halo, {{ $name }}!</h2>
                <p style="margin:0 0 12px 0; color:#4a5568; font-size:14px;">
                    Gunakan kode berikut untuk menyelesaikan login ke Lab SMABA.
                </p>
                <div style="margin:16px 0; padding:12px 16px; background:#f0f4ff; border:1px dashed #4c51bf; border-radius:6px; text-align:center;">
                    <span style="font-size:24px; letter-spacing:6px; font-weight:bold; color:#2d3748;">{{ $code }}</span>
                </div>
                <p style="margin:0 0 12px 0; color:#4a5568; font-size:14px;">
                    Kode ini berlaku selama {{ $expiresMinutes }} menit. Jika Anda tidak mencoba masuk, abaikan email ini.
                </p>
                <p style="margin:0; color:#a0aec0; font-size:12px;">Terima kasih.</p>
            </td>
        </tr>
    </table>
</body>
</html>
