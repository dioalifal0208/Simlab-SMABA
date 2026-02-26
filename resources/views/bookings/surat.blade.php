<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peminjaman Lab - {{ $booking->id }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.15;
            color: #000;
            background: #fff;
            margin: 0 auto;
            padding: 1cm 2cm; /* Margin standar surat resmi: 4,3,3,3 atau 2cm rata */
            width: 210mm;
            min-height: 297mm;
            box-sizing: border-box;
        }

        /* KOP SURAT */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center; /* Center the text content */
            position: relative; /* For absolute positioning of logo */
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            height: 110px; /* Fixed height to prevent shifting */
        }

        .logo-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .logo {
            width: 90px;
            height: auto;
        }

        .header-content {
            text-align: center;
            width: 100%; /* Ensure text takes full width to center properly relative to container */
        }

        .header-content h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }

        .header-content h3 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
            line-height: 1.2;
        }

        .header-content p {
            font-size: 10pt;
            margin: 0;
            line-height: 1.2;
        }

        /* TITLE */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
        }

        /* TABLES */
        table.form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        table.form-table td {
            vertical-align: top;
            padding: 2px 0;
            font-size: 11pt;
        }
        
        .number-col { width: 25px; }
        .label-col { width: 180px; }
        .separator-col { width: 15px; text-align: center; }

        /* CHECKBOXES & SIGNATURES */
        .checkbox-rect {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            position: relative;
            top: 2px;
        }
        
        .checkbox-rect.checked {
            background-color: #000; /* Simple fill for checked in print */
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* PRINT BUTTON */
        .print-button {
            position: fixed;
            top: 20px; right: 20px;
            background: #004ecc; color: white;
            border: none; padding: 10px 20px;
            border-radius: 5px; cursor: pointer;
            z-index: 9999;
        }

        @media print {
            body {
                margin: 0;
                box-shadow: none;
            }
            .print-button { display: none; }
            .header-container {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-button">Cetak Surat / Simpan PDF</button>

    <!-- KOP SURAT -->
    <div class="header-container">
        <div class="logo-wrapper">
             <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="logo">
        </div>
        <div class="header-content">
            <h2>PEMERINTAH PROVINSI JAWA TIMUR</h2>
            <h2>DINAS PENDIDIKAN</h2>
            <h3>SMA NEGERI 1 BABAT</h3>
            <p>Jl. Sumowiharjo No.1 Telp. 0322-3326616 Fax. (0322) 451201</p>
            <p>Email: smanegeri1babat.lmg@gmail.com</p>
        </div>
    </div>

    <!-- Double line border bottom handled by CSS .header -->

    <div class="title-section">
        FORMULIR PEMINJAMAN LABORATORIUM BIOLOGI<br>
        SMA NEGERI 1 BABAT
    </div>

    <div class="content">
        
        <!-- SECTION 1 -->
        <div style="margin-bottom: 5px;">
            <b style="font-size: 12pt;">• IDENTITAS PEMINJAM</b>
        </div>
        
        <table class="form-table">
            <tr>
                <td class="number-col">1.</td>
                <td class="label-col">Nama Lengkap</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->user->name }}</td>
            </tr>
            <tr>
                <td class="number-col">2.</td>
                <td class="label-col">Status</td>
                <td class="separator-col">:</td>
                <td>
                    <!-- Logic to check Guru or Siswa -->
                    <span class="checkbox-rect {{ $booking->user->role == 'guru' ? 'checked' : '' }}"></span> Guru &nbsp;&nbsp;&nbsp;
                    <span class="checkbox-rect {{ $booking->user->role == 'siswa' || $booking->user->role == 'user' ? 'checked' : '' }}"></span> Siswa
                </td>
            </tr>
            <tr>
                <td class="number-col">3.</td>
                <td class="label-col">NIP</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->user->nomor_induk ?? $booking->user->email }}</td> <!-- Fallback to email if no induk -->
            </tr>
            <tr>
                <td class="number-col">4.</td>
                <td class="label-col">Kelas / Jabatan</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->user->kelas ?? ucfirst($booking->user->role) }}</td> <!-- Prioritize Kelas field -->
            </tr>
            <tr>
                <td class="number-col">5.</td>
                <td class="label-col">Kontak (No. Hp / WA)</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->user->phone_number ?? '-' }}</td>
            </tr>
        </table>

        <!-- SECTION 2 -->
        <div style="margin-top: 15px; margin-bottom: 5px;">
            <b style="font-size: 12pt;">• KEPERLUAN PEMINJAMAN</b>
        </div>

        <table class="form-table">
            <tr>
                <td class="number-col">1.</td>
                <td class="label-col">Tujuan Kegiatan</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->tujuan_kegiatan }}</td>
            </tr>
            <tr>
                <td class="number-col">2.</td>
                <td class="label-col">Mata Pelajaran / Kegiatan</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->mata_pelajaran ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number-col">3.</td>
                <td class="label-col">Guru Pengampu*</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->guru_pengampu }}</td>
            </tr>
            <tr>
                <td class="number-col">4.</td>
                <td class="label-col">Tanggal Peminjaman</td>
                <td class="separator-col">:</td>
                <td>
                    {{ \Carbon\Carbon::parse($booking->waktu_mulai)->day }} / 
                    {{ \Carbon\Carbon::parse($booking->waktu_mulai)->month }} / 
                    {{ \Carbon\Carbon::parse($booking->waktu_mulai)->year }}
                </td>
            </tr>
            <tr>
                <td class="number-col">5.</td>
                <td class="label-col">Waktu Peminjaman</td>
                <td class="separator-col">:</td>
                <td>
                    Pukul {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') }} s/d 
                    {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <td class="number-col">6.</td>
                <td class="label-col">Ruang / Lab</td>
                <td class="separator-col">:</td>
                <td>Laboratorium {{ $booking->laboratorium }}</td>
            </tr>
            <tr>
                <td class="number-col">7.</td>
                <td class="label-col">Jumlah Peserta</td>
                <td class="separator-col">:</td>
                <td>{{ $booking->jumlah_peserta ?? '.........' }} Siswa</td>
            </tr>
        </table>

        <!-- SECTION 3 -->
        <div style="margin-top: 15px; margin-bottom: 5px;">
            <b style="font-size: 12pt;">• PENGEMBALIAN</b>
        </div>

        <table class="form-table">
            <tr>
                <td class="number-col">1.</td>
                <td class="label-col">Tanggal Pengembalian</td>
                <td class="separator-col">:</td>
                <td>
                    @if($booking->waktu_pengembalian)
                         {{ $booking->waktu_pengembalian->format('d') }} / 
                         {{ $booking->waktu_pengembalian->format('m') }} / 
                         {{ $booking->waktu_pengembalian->format('Y') }}
                    @else
                        ......... / .................... / {{ date('Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number-col">2.</td>
                <td class="label-col">Kondisi Ruangan / Lab**</td>
                <td class="separator-col">:</td>
                <td></td>
            </tr>
        </table>
        
        <div style="margin-left: 247px;"> <!-- Indent to align with colon roughly or just below -->
            <div><span class="checkbox-rect {{ in_array('Bersih dan Rapi', $booking->kondisi_lab ?? []) ? 'checked' : '' }}"></span> Bersih dan Rapi</div>
            <div><span class="checkbox-rect {{ in_array('Ada Sampah / Belum Dibersihkan', $booking->kondisi_lab ?? []) ? 'checked' : '' }}"></span> Ada Sampah / Belum Dibersihkan</div>
            <div><span class="checkbox-rect {{ in_array('Alat Tidak Kembali ke Posisi Semula', $booking->kondisi_lab ?? []) ? 'checked' : '' }}"></span> Alat Tidak Kembali ke Posisi Semula</div>
            <div><span class="checkbox-rect {{ in_array('Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)', $booking->kondisi_lab ?? []) ? 'checked' : '' }}"></span> Kerusakan Pada fasilitas (Kursi, Meja, Alat, dll.)</div>
        </div>

        <!-- SIGNATURES -->
        <div style="margin-top: 15px; margin-bottom: 5px;">
             <b style="font-size: 12pt;">• TANDA TANGAN</b>
            <div class="signature-box">
                <p style="margin-bottom: 5px;">Laboran</p>
                <br>
                {{-- QR Code TTE --}}
                <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(90)->merge(public_path('images/logo-smaba.webp'), 0.3, true)->generate(route('bookings.verify', $booking->id))) }}" alt="QR Validation">
                <br>
                <div class="signature-name" style="font-size: 8pt; margin-top: 5px;">(Ditandatangani Secara Elektronik)</div>
            </div>
        </div>

        <div class="footnote">
            *diisi ketika dipinjam oleh siswa , **wajib dipilih
        </div>

    </div>

</body>
</html>

