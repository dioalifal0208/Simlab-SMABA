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
        Schema::create('practicum_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul modul (misal: "Praktikum Titrasi Asam Basa")
            $table->text('description')->nullable(); // Deskripsi atau langkah-langkah
            $table->foreignId('user_id')->constrained()->comment('User (guru/admin) yang membuat modul'); // Siapa yang membuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practicum_modules');
    }
};