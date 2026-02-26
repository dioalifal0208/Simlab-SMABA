<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update existing data to match new casing before changing enum
        DB::table('items')->where('kondisi', 'Baik')->update(['kondisi' => 'baik']);
        DB::table('items')->where('kondisi', 'Kurang Baik')->update(['kondisi' => 'kurang baik']);
        // 'Rusak' stays 'Rusak'

        // 2. Change the enum definition
        Schema::table('items', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'kurang baik', 'Rusak'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->enum('kondisi', ['Baik', 'Kurang Baik', 'Rusak'])->change();
        });

        DB::table('items')->where('kondisi', 'baik')->update(['kondisi' => 'Baik']);
        DB::table('items')->where('kondisi', 'kurang baik')->update(['kondisi' => 'Kurang Baik']);
    }
};
