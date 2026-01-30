<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Peminjaman Lab - {{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.3;
            color: #000;
            background: #fff;
            margin: 0 auto;
            padding: 1cm 2cm;
            width: 210mm; /* Force A4 width on screen for preview */
            box-sizing: border-box;
            background-color: white;
            min-height: 297mm; /* Force A4 height on screen */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Preview shadow */
        }
        
        @media print {
            @page {
                size: A4 portrait;
                margin: 0; /* Let body padding handle margins */
            }
            body {
                width: 210mm;
                height: 297mm;
                padding: 1.5cm 2cm; /* Adjust padding for print */
                margin: 0;
                box-shadow: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-button {
                display: none;
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
