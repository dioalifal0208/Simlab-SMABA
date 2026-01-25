{{-- Email Layout Base --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lab SMABA')</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
            color: #374151;
            line-height: 1.6;
        }
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #1e3a8a;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #3b82f6;
        }
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .item-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        .item-list li {
            padding: 10px;
            background-color: #f9fafb;
            margin-bottom: 8px;
            border-radius: 4px;
            border-left: 3px solid #3b82f6;
        }
        .highlight {
            color: #1e3a8a;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔬 Lab SMABA</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Sistem Manajemen Laboratorium</p>
        </div>
        
        <div class="content">
            @yield('content')
        </div>
        
        <div class="footer">
            <p><strong>Lab SMABA</strong></p>
            <p>SMA Negeri Bahrul Amin</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 15px;">
                Jika ada pertanyaan, hubungi admin laboratorium di WhatsApp atau email resmi sekolah.
            </p>
        </div>
    </div>
</body>
</html>
