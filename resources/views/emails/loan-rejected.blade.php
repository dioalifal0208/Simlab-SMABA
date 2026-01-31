@extends('emails.layout')

@section('title', 'Peminjaman Ditolak')

@section('content')
    <h2 style="color: #ef4444; margin-top: 0;">❌ Peminjaman Tidak Dapat Disetujui</h2>
    
    <p>Halo <strong>{{ $loan->user->name }}</strong>,</p>
    
    <p>Mohon maaf, pengajuan peminjaman Anda <span class="highlight" style="color: #ef4444;">tidak dapat disetujui</span> oleh admin laboratorium.</p>
    
    <div class="info-box" style="border-left-color: #ef4444; background-color: #fef2f2;">
        <p style="margin: 0 0 10px 0;"><strong>📋 Detail Pengajuan:</strong></p>
        <p style="margin: 5px 0;">
            <strong>Tanggal Pinjam:</strong> {{ $loan->tanggal_pinjam->format('d F Y') }}<br>
            <strong>Tanggal Kembali:</strong> {{ $loan->tanggal_estimasi_kembali->format('d F Y') }}<br>
            <strong>Laboratorium:</strong> {{ $loan->laboratorium }}
        </p>
    </div>
    
    <p><strong>📦 Item yang Diajukan:</strong></p>
    <ul class="item-list">
        @foreach($loan->items as $item)
            <li style="border-left-color: #ef4444;">
                <strong>{{ $item->nama_alat }}</strong>
                @if($item->pivot->jumlah)
                    <br><small>Jumlah: {{ $item->pivot->jumlah }} {{ $item->satuan }}</small>
                @endif
            </li>
        @endforeach
    </ul>
    
    @if($loan->admin_notes)
    <div class="info-box" style="border-left-color: #f59e0b;">
        <p style="margin: 0;"><strong>📝 Catatan dari Admin:</strong></p>
        <p style="margin: 5px 0 0 0;">{{ $loan->admin_notes }}</p>
    </div>
    @endif
    
    <p style="margin-top: 25px;"><strong>💡 Apa yang bisa dilakukan:</strong></p>
    <ul style="color: #6b7280; font-size: 14px;">
        <li>Hubungi admin laboratorium untuk informasi lebih lanjut</li>
        <li>Pastikan item yang diajukan tersedia dan sesuai kebutuhan</li>
        <li>Ajukan peminjaman ulang dengan item atau tanggal yang berbeda</li>
        <li>Periksa kembali jadwal penggunaan laboratorium</li>
    </ul>
    
    <div style="text-align: center;">
        <a href="{{ route('loans.create') }}" class="cta-button" style="background-color: #3b82f6;">
            Ajukan Peminjaman Baru
        </a>
    </div>
    
    <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
        Terima kasih atas pengertian Anda. Jangan ragu untuk mengajukan kembali!
    </p>
@endsection
