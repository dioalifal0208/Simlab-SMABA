{{-- Email Layout Base — Full Inline CSS untuk kompatibilitas email client --}}
<!DOCTYPE html>
<html lang="id" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Lab SMABA')</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">

    {{-- Outer Table --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 24px 16px;">

                {{-- Email Container --}}
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 32px 24px; text-align: center;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <span style="font-size: 28px;">🔬</span>
                                        <h1 style="margin: 8px 0 0 0; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: 0.5px;">Lab SMABA</h1>
                                        <p style="margin: 6px 0 0 0; font-size: 13px; color: rgba(255,255,255,0.85);">Sistem Manajemen Laboratorium</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Category Badge (opsional) --}}
                    @hasSection('badge')
                    <tr>
                        <td style="padding: 20px 28px 0 28px;">
                            @yield('badge')
                        </td>
                    </tr>
                    @endif

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 28px; color: #374151; font-size: 15px; line-height: 1.7;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 28px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 600; color: #6b7280;">Lab SMABA</p>
                            <p style="margin: 0 0 12px 0; font-size: 12px; color: #9ca3af;">SMA Negeri 1 Babat Lamongan</p>
                            <p style="margin: 0; font-size: 11px; color: #9ca3af; line-height: 1.5;">
                                Email ini dikirim otomatis oleh sistem Lab SMABA.<br>
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>

                {{-- Pre-footer link --}}
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width: 600px;">
                    <tr>
                        <td style="padding: 16px 0; text-align: center;">
                            <p style="margin: 0; font-size: 11px; color: #9ca3af;">
                                Jika ada pertanyaan, hubungi admin laboratorium.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
