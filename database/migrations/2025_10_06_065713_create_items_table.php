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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('nama_alat');
        $table->integer('jumlah');
        $table->string('satuan');
        $table->enum('kondisi', ['Baik', 'Kurang Baik', 'Rusak']);
        $table->string('lokasi_penyimpanan');
        $table->string('kode_inventaris')->unique()->nullable();
        $table->year('tahun_pengadaan')->nullable();
        $table->text('keterangan')->nullable();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang menambahkan data ini
        $table->timestamps(); // Otomatis membuat kolom created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
