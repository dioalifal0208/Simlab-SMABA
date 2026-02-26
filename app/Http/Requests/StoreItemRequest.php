<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya admin yang bisa create items
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai',
            'jumlah' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:baik,kurang baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.max' => 'Nama alat tidak boleh lebih dari :max karakter.',
            
            'tipe.required' => 'Tipe alat wajib dipilih.',
            'tipe.in' => 'Tipe alat harus berupa "Alat" atau "Bahan Habis Pakai".',
            
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari :min.',
            
            'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
            'stok_minimum.min' => 'Stok minimum tidak boleh kurang dari :min.',
            
            'satuan.required' => 'Satuan wajib diisi.',
            'satuan.max' => 'Satuan tidak boleh lebih dari :max karakter.',
            
            'kondisi.required' => 'Kondisi alat wajib dipilih.',
            'kondisi.in' => 'Kondisi harus berupa "baik", "kurang baik", atau "Rusak".',
            
            'lokasi_penyimpanan.required' => 'Lokasi penyimpanan wajib diisi.',
            'lokasi_penyimpanan.max' => 'Lokasi penyimpanan tidak boleh lebih dari :max karakter.',
            
            'laboratorium.required' => 'Laboratorium wajib dipilih.',
            'laboratorium.in' => 'Laboratorium harus berupa "Biologi", "Fisika", atau "Bahasa".',
            
            'photos.array' => 'Foto harus berupa array.',
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.mimes' => 'Format foto harus jpeg, png, jpg, atau webp.',
            'photos.*.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_alat' => 'nama alat',
            'tipe' => 'tipe',
            'jumlah' => 'jumlah',
            'stok_minimum' => 'stok minimum',
            'satuan' => 'satuan',
            'kondisi' => 'kondisi',
            'lokasi_penyimpanan' => 'lokasi penyimpanan',
            'deskripsi' => 'deskripsi',
            'laboratorium' => 'laboratorium',
            'photos' => 'foto',
        ];
    }
}
