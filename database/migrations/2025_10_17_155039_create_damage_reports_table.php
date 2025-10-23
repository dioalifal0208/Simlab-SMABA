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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Terhubung ke item yg rusak
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke user yg melapor
            $table->text('description'); // Deskripsi kerusakan
            $table->string('photo')->nullable(); // Foto kerusakan (opsional)
            $table->string('status')->default('Dilaporkan'); // Status: Dilaporkan, Diverifikasi, Diperbaiki
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};