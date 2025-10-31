@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])

{{-- 
    PENAMBAHAN: 
    Blok <style> ini akan menimpa warna default Laravel (biru) 
    dengan warna smaba-dark-blue Anda.
--}}
<style>
    .button-primary {
        background-color: #0F172A !important; /* <-- Ganti #0F172A dengan kode hex Anda jika berbeda */
        border-color: #0F172A !important;
    }
</style>

<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener">{!! $slot !!}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>