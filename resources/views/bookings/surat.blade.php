<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peminjaman Lab - {{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.3; /* Adjusted for tighter spacing like the image */
            color: #000;
            background: #fff;
            margin: 0;
            padding: 1cm 2cm; /* Standard margin for formal letters */
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000; /* Double line */
            padding-bottom: 5px;
            margin-bottom: 10px;
            position: relative;
        }
        .logo {
            width: 90px;
            height: auto;
            position: absolute;
            left: 0;
            top: 5px;
        }
        .header-text {
            /* padding-left: 100px; Center text properly even with logo absolute */
        }
        .header-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }
        .header-text h3 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
        }
        .header-text p {
            font-size: 10pt;
            margin: 0;
        }
        .header-text .email {
            color: blue;
            text-decoration: underline;
        }
        
        .title-section {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 12pt;
        }

        .content {
            font-size: 11pt;
        }
        
        /* Numbered list styling to match image */
        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            list-style-type: disc; /* Bullet point */
        }
        
        ul {
            list-style-type: none; /* We will use numbering manually or table */
            padding-left: 0;
            margin: 0;
        }

        table.form-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 15px; /* Indent slightly */
        }
        
        table.form-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        
        .number-col {
            width: 20px;
        }
        
        .label-col {
            width: 200px;
        }
        
        .separator-col {
            width: 15px;
            text-align: center;
        }
        
        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 200px;
            width: 100%; /* Fill remainder */
            height: 14px; /* Align check */
        }

        .checkbox-rect {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 10px;
        }

        /* Checkbox checkmark simulation for checked items */
        .checked::after {
            content: "✓";
            font-size: 10px;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
        }

        .signature-name {
            margin-top: 70px; /* Space for signature */
            display: block;
            /* text-decoration: underline; Optional based on preference, image shows bracket names mostly or no underline? Image shows (.....) */
        }
        
        .footnote {
            margin-top: 50px;
            font-size: 9pt;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #004ecc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-family: sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 1cm;
            }
            .print-button {
                display: none;
            }
            @page {
                size: A4;
                margin: 0;
            }
            .header-text .email {
                color: #000;
                text-decoration: none;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-button">Cetak Surat / Simpan PDF</button>

    <div class="header">
        <img src="{{ asset('images/logo-smaba.webp') }}" alt="Logo" class="logo">
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI JAWA TIMUR</h2>
            <h2>DINAS PENDIDIKAN</h2>
            <h3>SMA NEGERI 1 BABAT</h3>
            <p>Jl. Sumowiharjo No.1 Telp. 0322-3326616 Fax. (0322) 451201</p>
            <p>Email: <span class="email">smanegeri1babat.lmg@gmail.com</span></p>
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
        </div>

        <div class="signature-section">
            <div class="signature-box">
                Peminjam<br>
                <br>
                <div class="signature-name">( {{ $booking->user->name }} )</div>
            </div>
            <div class="signature-box">
                Guru Pengampu<br>
                <br>
                <!-- If the borrower IS the teacher, this might be redundant, but following format -->
                <div class="signature-name">( ........................................ )</div>
            </div>
            <div class="signature-box">
                Laboran<br>
                <br>
                <div class="signature-name">Dio Alif Alfarizi I</div>
            </div>
        </div>

        <div class="footnote">
            *diisi ketika dipinjam oleh siswa , **wajib dipilih
        </div>

    </div>

</body>
</html>
