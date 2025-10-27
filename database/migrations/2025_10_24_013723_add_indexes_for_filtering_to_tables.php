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
        Schema::table('items', function (Blueprint $table) {
            $table->index('kondisi');
            $table->index('tipe');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex(['kondisi']);
            $table->dropIndex(['tipe']);
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};