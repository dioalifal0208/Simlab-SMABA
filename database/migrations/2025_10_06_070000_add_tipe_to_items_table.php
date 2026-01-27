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
            if (!Schema::hasColumn('items', 'tipe')) {
                $table->string('tipe')->default('Alat')->after('nama_alat');
            }
            if (!Schema::hasColumn('items', 'laboratorium')) {
                $table->string('laboratorium')->nullable()->after('lokasi_penyimpanan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('items', 'laboratorium')) {
                $table->dropColumn('laboratorium');
            }
        });
    }
};
