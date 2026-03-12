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
        Schema::table('items', function (Blueprint $table) {
            $table->string('dokumen_tipe')->nullable()->after('deskripsi'); // manual_book, sop_ik, msds
            $table->string('dokumen_path')->nullable()->after('dokumen_tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['dokumen_tipe', 'dokumen_path']);
        });
    }
};
