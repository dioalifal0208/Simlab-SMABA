<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Semua authenticated users bisa create booking
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
            'guru_pengampu' => 'required|string|max:255',
            'tujuan_kegiatan' => 'required|string',
            'mata_pelajaran' => 'nullable|string|max:255',
            
            // Tambahan untuk update profile user on-the-fly
            'nomor_induk' => 'nullable|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:20',

            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa',
            'waktu_mulai' => 'required|date',
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
            'guru_pengampu.required' => 'Nama guru pengampu wajib diisi.',
            'guru_pengampu.max' => 'Nama guru pengampu tidak boleh lebih dari :max karakter.',
            
            'tujuan_kegiatan.required' => 'Tujuan kegiatan wajib diisi.',
            
            'mata_pelajaran.string' => 'Mata pelajaran harus berupa teks.',
            'mata_pelajaran.max' => 'Mata pelajaran tidak boleh lebih dari :max karakter.',
            
            'laboratorium.required' => 'Laboratorium wajib dipilih.',
            'laboratorium.in' => 'Laboratorium harus berupa "Biologi", "Fisika", atau "Bahasa".',
            
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date' => 'Waktu mulai harus berupa tanggal dan waktu yang valid.',
            
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.date' => 'Waktu selesai harus berupa tanggal dan waktu yang valid.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
            
            'jumlah_peserta.integer' => 'Jumlah peserta harus berupa angka.',
            'jumlah_peserta.min' => 'Jumlah peserta minimal :min orang.',
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
            'guru_pengampu' => 'guru pengampu',
            'tujuan_kegiatan' => 'tujuan kegiatan',
            'mata_pelajaran' => 'mata pelajaran',
            'laboratorium' => 'laboratorium',
            'waktu_mulai' => 'waktu mulai',
            'waktu_selesai' => 'waktu selesai',
            'jumlah_peserta' => 'jumlah peserta',
        ];
    }
}
