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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang mengajukan booking
        $table->string('guru_pengampu'); // Nama guru pengampu mata pelajaran
        $table->string('tujuan_kegiatan');
        $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        $table->dateTime('waktu_mulai'); // Waktu mulai penggunaan lab
        $table->dateTime('waktu_selesai'); // Waktu selesai penggunaan lab
        $table->integer('jumlah_peserta')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
