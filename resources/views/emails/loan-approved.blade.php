@extends('emails.layout')

@section('title', 'Peminjaman Disetujui')

@section('content')
    <h2 style="color: #10b981; margin-top: 0;">✅ Peminjaman Anda Disetujui!</h2>
    
    <p>Halo <strong>{{ $loan->user->name }}</strong>,</p>
    
    <p>Kabar baik! Pengajuan peminjaman Anda telah <span class="highlight">disetujui</span> oleh admin laboratorium.</p>
    
    <div class="info-box">
        <p style="margin: 0 0 10px 0;"><strong>📋 Detail Peminjaman:</strong></p>
        <p style="margin: 5px 0;">
            <strong>Tanggal Pinjam:</strong> {{ $loan->tanggal_pinjam->format('d F Y') }}<br>
            <strong>Tanggal Kembali:</strong> {{ $loan->tanggal_estimasi_kembali->format('d F Y') }}<br>
            <strong>Laboratorium:</strong> {{ $loan->laboratorium }}
        </p>
    </div>
    
    <p><strong>📦 Item yang Dipinjam:</strong></p>
    <ul class="item-list">
        @foreach($loan->items as $item)
            <li>
                <strong>{{ $item->nama_alat }}</strong>
                @if($item->pivot->jumlah)
                    <br><small>Jumlah: {{ $item->pivot->jumlah }} {{ $item->satuan }}</small>
                @endif
            </li>
        @endforeach
    </ul>
    
    @if($loan->catatan)
    <div class="info-box" style="border-left-color: #f59e0b;">
        <p style="margin: 0;"><strong>📝 Catatan Anda:</strong></p>
        <p style="margin: 5px 0 0 0;">{{ $loan->catatan }}</p>
    </div>
    @endif
    
    <p style="margin-top: 25px;"><strong>⚠️ Mohon Diperhatikan:</strong></p>
    <ul style="color: #6b7280; font-size: 14px;">
        <li>Pastikan mengambil item pada tanggal yang telah ditentukan</li>
        <li>Jaga kondisi item dengan baik selama peminjaman</li>
        <li>Kembalikan item tepat waktu sesuai tanggal yang tertera</li>
        <li>Laporkan segera jika terjadi kerusakan</li>
    </ul>
    
    <div style="text-align: center;">
        <a href="{{ route('loans.show', $loan->id) }}" class="cta-button">
            Lihat Detail Peminjaman
        </a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        Terima kasih telah menggunakan sistem Lab SMABA. Selamat belajar!
    </p>
@endsection

