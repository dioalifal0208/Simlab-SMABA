<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Terhubung ke item
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke user (admin)
            $table->date('tanggal_perawatan');
            $table->string('hasil');
            $table->text('masalah_ditemukan');
            $table->text('tindakan_perbaikan');
            $table->integer('biaya')->nullable()->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};