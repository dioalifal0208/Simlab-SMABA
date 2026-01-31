<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UserImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Mencocokkan header CSV (kiri) dengan kolom database (kanan)
        return new User([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'role'     => $row['role'] ?? 'guru', // Default role adalah 'guru' jika dikosongkan
            'laboratorium' => $row['laboratorium'] ?? null,
            'password' => Hash::make($row['password']), // PENTING: Password di-hash
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email', // Pastikan email unik
            'role' => 'nullable|in:admin,guru', // Hanya izinkan admin atau guru
            'laboratorium' => 'nullable|in:Biologi,Fisika,Bahasa',
            'password' => 'required|string|min:8', // Wajibkan password minimal 8 karakter
        ];
    }

    /**
     * Mengganti nama atribut untuk pesan error
     */
    public function customValidationAttributes()
    {
        return [
            'nama' => 'Nama',
            'email' => 'Email',
            'role' => 'Role',
            'password' => 'Password',
        ];
    }
}
