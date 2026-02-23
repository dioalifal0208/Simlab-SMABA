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
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_estimasi_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:items,id',
            'jumlah.*' => 'nullable|integer|min:1',
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa',
            'catatan' => 'nullable|string|max:1000',
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
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.date' => 'Tanggal pinjam harus berupa tanggal yang valid.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam harus hari ini atau setelahnya.',
            
            'tanggal_estimasi_kembali.required' => 'Tanggal estimasi kembali wajib diisi.',
            'tanggal_estimasi_kembali.date' => 'Tanggal estimasi kembali harus berupa tanggal yang valid.',
            'tanggal_estimasi_kembali.after_or_equal' => 'Tanggal estimasi kembali harus sama dengan atau setelah tanggal pinjam.',
            
            'items.required' => 'Minimal harus memilih satu item untuk dipinjam.',
            'items.array' => 'Items harus berupa array.',
            'items.min' => 'Minimal harus memilih :min item.',
            'items.*.exists' => 'Item yang dipilih tidak valid.',
            
            'jumlah.*.integer' => 'Jumlah harus berupa angka.',
            'jumlah.*.min' => 'Jumlah minimal :min.',
            
            'laboratorium.required' => 'Laboratorium wajib dipilih.',
            'laboratorium.in' => 'Laboratorium harus berupa "Biologi", "Fisika", atau "Bahasa".',
            
            'catatan.max' => 'Catatan tidak boleh lebih dari :max karakter.',
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
            'tanggal_pinjam' => 'tanggal pinjam',
            'tanggal_estimasi_kembali' => 'tanggal estimasi kembali',
            'items' => 'item',
            'jumlah.*' => 'jumlah',
            'laboratorium' => 'laboratorium',
            'catatan' => 'catatan',
        ];
    }
}
