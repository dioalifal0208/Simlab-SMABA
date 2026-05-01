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
        // Set default values for existing rows
        \Illuminate\Support\Facades\DB::table('items')->whereNull('tahun_pengadaan')->update(['tahun_pengadaan' => date('Y')]);
        
        $items = \Illuminate\Support\Facades\DB::table('items')->whereNull('kode_inventaris')->get();
        foreach($items as $item) {
            \Illuminate\Support\Facades\DB::table('items')->where('id', $item->id)->update([
                'kode_inventaris' => 'INV-OLD-' . str_pad($item->id, 5, '0', STR_PAD_LEFT)
            ]);
        }

        Schema::table('items', function (Blueprint $table) {
            $table->string('kode_inventaris')->nullable(false)->change();
            $table->year('tahun_pengadaan')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('kode_inventaris')->nullable()->change();
            $table->year('tahun_pengadaan')->nullable()->change();
        });
    }
};
