<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Item apa yang diminta
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang meminta
            $table->string('status')->default('requested'); // Status: 'requested', 'fulfilled'
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('stock_requests');
    }
};