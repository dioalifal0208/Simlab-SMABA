<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Semua authenticated users bisa create loan requests
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Field untuk Item
            'items' => 'required|array|min:1',
            'items.*' => 'exists:items,id',
            'jumlah.*' => 'nullable|integer|min:1',
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa,Komputer 1,Komputer 2,Komputer 3,Komputer 4',
            'catatan' => 'nullable|string|max:1000',

            // Field untuk Booking Lab
            'guru_pengampu' => 'required|string|max:255',
            'tujuan_kegiatan' => 'required|string',
            'mata_pelajaran' => 'nullable|string|max:255',
            
            // Profile user update
            'nomor_induk' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',

            'waktu_mulai' => 'required|date|after_or_equal:today',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'jumlah_peserta' => 'nullable|integer|min:1',
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
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date' => 'Waktu mulai harus berupa tanggal yang valid.',
            'waktu_mulai.after_or_equal' => 'Waktu mulai harus hari ini atau setelahnya.',
            
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.date' => 'Waktu selesai harus berupa tanggal yang valid.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            
            'items.required' => 'Minimal harus memilih satu item untuk dipinjam.',
            'items.array' => 'Items harus berupa array.',
            'items.min' => 'Minimal harus memilih :min item.',
            'items.*.exists' => 'Item yang dipilih tidak valid.',
            
            'jumlah.*.integer' => 'Jumlah harus berupa angka.',
            'jumlah.*.min' => 'Jumlah minimal :min.',
            
            'laboratorium.required' => 'Laboratorium wajib dipilih.',
            'laboratorium.in' => 'Laboratorium harus berupa "Biologi", "Fisika", "Bahasa", atau "Komputer 1-4".',
            
            'catatan.max' => 'Catatan tidak boleh lebih dari :max karakter.',

            'guru_pengampu.required' => 'Nama guru pengampu wajib diisi.',
            'tujuan_kegiatan.required' => 'Tujuan kegiatan wajib diisi.',
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
            'waktu_mulai' => 'waktu mulai',
            'waktu_selesai' => 'waktu selesai',
            'items' => 'item',
            'jumlah.*' => 'jumlah',
            'laboratorium' => 'laboratorium',
            'catatan' => 'catatan',
            'guru_pengampu' => 'guru pengampu',
            'tujuan_kegiatan' => 'tujuan kegiatan',
            'jumlah_peserta' => 'jumlah peserta',
        ];
    }
}
