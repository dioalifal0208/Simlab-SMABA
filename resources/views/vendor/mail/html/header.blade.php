<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
            {{-- 
                PENTING: Email tidak bisa menggunakan asset() biasa.
                Kita harus menggunakan $message->embed() untuk menempelkan gambar.
            --}}
            @if (isset($message))
                <img src="{{ $message->embed(public_path('images/logo-smaba.webp')) }}" 
                     alt="{{ config('app.name', 'LAB-SMABA') }} Logo" 
                     style="width: 70px; height: auto; border: 0;">
            @else
                {{-- Tampil sebagai teks jika gambar tidak bisa di-load --}}
                <h1 style="color: #3d4852; font-size: 19px; font-weight: bold;">
                    {{ config('app.name', 'LAB-SMABA') }}
                </h1>
            @endif
        </a>
    </td>
</tr>
