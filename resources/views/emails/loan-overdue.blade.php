@extends('emails.layout')

@section('title', 'Peminjaman Terlambat')

@section('content')
    <h2 style="color: #dc2626; margin-top: 0;">⚠️ Peminjaman Anda Sudah Melewati Batas Waktu</h2>
    
    <p>Halo <strong>{{ $loan->user->name }}</strong>,</p>
    
    <p>Peminjaman Anda telah <span class="highlight" style="color: #dc2626;">melewati batas waktu pengembalian</span>.</p>
    
    <div class="info-box" style="border-left-color: #dc2626; background-color: #fef2f2;">
        <p style="margin: 0 0 10px 0;"><strong>🕒 Status Keterlambatan:</strong></p>
        <p style="margin: 5px 0; font-size: 18px; color: #dc2626;">
            <strong>Terlambat {{ $daysOverdue }} hari</strong>
        </p>
        <p style="margin: 10px 0 0 0;">
            <strong>Tanggal Seharusnya Kembali:</strong> {{ $loan->tanggal_estimasi_kembali->format('d F Y') }}
        </p>
    </div>
    
    <p><strong>📦 Item yang Dipinjam:</strong></p>
    <ul class="item-list">
        @foreach($loan->items as $item)
            <li style="border-left-color: #dc2626;">
                <strong>{{ $item->nama_alat }}</strong>
                @if($item->pivot->jumlah)
                    <br><small>Jumlah: {{ $item->pivot->jumlah }} {{ $item->satuan }}</small>
                @endif
            </li>
        @endforeach
    </ul>
    
    <div class="info-box" style="border-left-color: #dc2626; background-color: #fef2f2;">
        <p style="margin: 0;"><strong>⚠️ Tindakan Segera Diperlukan:</strong></p>
        <p style="margin: 10px 0 0 0; color: #991b1b; font-size: 14px;">
            Mohon segera mengembalikan item yang dipinjam. Keterlambatan berkepanjangan dapat mempengaruhi:
        </p>
        <ul style="color: #991b1b; font-size: 14px; margin: 10px 0 0 20px;">
            <li>Hak peminjaman di masa mendatang</li>
            <li>Ketersediaan alat untuk pengguna lain</li>
            <li>Jadwal praktikum yang sudah terencana</li>
        </ul>
    </div>
    
    <p style="margin-top: 25px;"><strong>📞 Cara Menyelesaikan:</strong></p>
    <ul style="color: #6b7280; font-size: 14px;">
        <li><strong>Segera kembalikan item</strong> ke laboratorium {{ $loan->laboratorium }}</li>
        <li>Hubungi admin laboratorium jika ada kendala</li>
        <li>Laporkan kondisi item saat pengembalian</li>
        <li>Pastikan laboran/admin mengkonfirmasi pengembalian</li>
    </ul>
    
    <div style="text-align: center;">
        <a href="{{ route('loans.show', $loan->id) }}" class="cta-button" style="background-color: #dc2626;">
            Lihat Detail Peminjaman
        </a>
    </div>
    
    <p style="margin-top: 30px; padding: 15px; background-color: #fef2f2; border-radius: 6px; border-left: 4px solid #dc2626; color: #991b1b; font-size: 14px;">
        <strong>Penting:</strong> Jika item hilang atau rusak, mohon segera laporkan ke admin laboratorium untuk mencari solusi terbaik.
    </p>
    
    <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
        Terima kasih atas perhatian dan kerjasama Anda.
    </p>
@endsection

