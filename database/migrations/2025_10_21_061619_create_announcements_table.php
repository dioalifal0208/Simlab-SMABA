<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin yang mem-posting
            $table->text('message'); // Isi pengumuman
            $table->string('status')->default('inactive'); // Status: 'active' atau 'inactive'
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};