<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang meminjam
        $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable(); // Diisi saat alat sudah kembali
        $table->text('catatan')->nullable(); // Catatan tambahan dari peminjam atau admin
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
