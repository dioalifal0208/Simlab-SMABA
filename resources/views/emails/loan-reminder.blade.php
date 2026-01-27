@extends('emails.layout')

@section('title', 'Pengingat Peminjaman')

@section('content')
    <h2 style="color: #f59e0b; margin-top: 0;">⏰ Pengingat: Peminjaman Jatuh Tempo Besok</h2>
    
    <p>Halo <strong>{{ $loan->user->name }}</strong>,</p>
    
    <p>Ini adalah pengingat bahwa peminjaman Anda akan <span class="highlight" style="color: #f59e0b;">jatuh tempo besok</span>.</p>
    
    <div class="info-box" style="border-left-color: #f59e0b; background-color: #fffbeb;">
        <p style="margin: 0 0 10px 0;"><strong>📅 Tanggal Pengembalian:</strong></p>
        <p style="margin: 5px 0; font-size: 18px; color: #f59e0b;">
            <strong>{{ $loan->tanggal_estimasi_kembali->format('d F Y') }}</strong>
        </p>
        <p style="margin: 10px 0 0 0; font-size: 14px; color: #92400e;">
            Mohon kembalikan item sebelum pukul 15:00 WIB
        </p>
    </div>
    
    <p><strong>📦 Item yang Dipinjam:</strong></p>
    <ul class="item-list">
        @foreach($loan->items as $item)
            <li style="border-left-color: #f59e0b;">
                <strong>{{ $item->nama_alat }}</strong>
                @if($item->pivot->jumlah)
                    <br><small>Jumlah: {{ $item->pivot->jumlah }} {{ $item->satuan }}</small>
                @endif
            </li>
        @endforeach
    </ul>
    
    <p style="margin-top: 25px;"><strong>✅ Checklist Sebelum Mengembalikan:</strong></p>
    <ul style="color: #6b7280; font-size: 14px;">
        <li>Pastikan semua item dalam kondisi baik dan berfungsi</li>
        <li>Bersihkan item dari kotoran atau debu</li>
        <li>Kembalikan item ke lokasi penyimpanan yang tepat</li>
        <li>Laporkan jika ada kerusakan atau kehilangan segera</li>
        <li>Konfirmasi pengembalian dengan laboran/admin</li>
    </ul>
    
    <div style="text-align: center;">
        <a href="{{ route('loans.show', $loan->id) }}" class="cta-button" style="background-color: #f59e0b;">
            Lihat Detail Peminjaman
        </a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        Terima kasih atas kerjasama Anda dalam menjaga fasilitas laboratorium!
    </p>
@endsection
