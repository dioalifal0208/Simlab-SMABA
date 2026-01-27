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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50); // created, updated, deleted, login, logout, etc
            $table->string('model', 100)->nullable(); // Item, Loan, Booking, User, Auth, etc
            $table->unsignedBigInteger('model_id')->nullable(); // ID of affected record
            $table->json('details')->nullable(); // old & new values, additional info
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('action');
            $table->index('model');
            $table->index('created_at');
            $table->index(['model', 'model_id']); // Compound index for finding changes to specific record
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
