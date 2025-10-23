<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- PENTING: Tambahkan ini

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom 'status' untuk menambahkan 'Terlambat' ke daftar ENUM
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed', 'Terlambat') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke definisi ENUM sebelumnya jika di-rollback
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'pending'");
    }
};