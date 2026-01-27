<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya admin yang bisa import items
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
            'file' => 'required|mimes:csv,xlsx,xls|max:5120', // Max 5MB
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
            'file.required' => 'File wajib diunggah.',
            'file.mimes' => 'Format file harus CSV, XLS, atau XLSX.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
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
            'file' => 'file',
        ];
    }
}
