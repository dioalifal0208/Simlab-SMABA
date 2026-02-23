<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pastikan hanya admin dan guru yang menjadi opsi role.
     */
    public function up(): void
    {
        // Konversi user yang sebelumnya berperan sebagai siswa menjadi guru.
        DB::table('users')
            ->where('role', 'siswa')
            ->update(['role' => 'guru']);

        // Perketat enum role hanya untuk admin dan guru, default guru.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','guru') NOT NULL DEFAULT 'guru'");
    }

    /**
     * Kembalikan enum ke kondisi semula (admin, guru, siswa).
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','guru','siswa') NOT NULL DEFAULT 'siswa'");
    }
};
