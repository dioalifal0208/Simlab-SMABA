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
            'laboratorium' => 'laboratorium',
            'waktu_mulai' => 'waktu mulai',
            'waktu_selesai' => 'waktu selesai',
            'jumlah_peserta' => 'jumlah peserta',
        ];
    }
}
