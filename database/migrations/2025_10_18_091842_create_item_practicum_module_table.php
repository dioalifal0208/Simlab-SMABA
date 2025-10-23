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
        // Nama tabel biasanya gabungan nama model dalam urutan alfabet dan bentuk singular
        Schema::create('item_practicum_module', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('practicum_module_id')->constrained()->onDelete('cascade');
            // Anda bisa menambahkan kolom lain di sini jika perlu, misal jumlah item yg dibutuhkan
            // $table->integer('quantity_needed')->default(1);
            $table->timestamps(); // Opsional, tapi bisa berguna
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_practicum_module');
    }
};