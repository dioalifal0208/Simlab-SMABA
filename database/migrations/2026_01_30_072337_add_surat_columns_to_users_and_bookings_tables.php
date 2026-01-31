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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_induk')->nullable()->after('email'); // NIP atau NIS
            $table->string('phone_number')->nullable()->after('nomor_induk'); // No HP
            $table->string('kelas')->nullable()->after('role'); // Jabatan atau Kelas spesifik
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('mata_pelajaran')->nullable()->after('tujuan_kegiatan'); // Mata Pelajaran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nomor_induk', 'phone_number', 'kelas']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('mata_pelajaran');
        });
    }
};
