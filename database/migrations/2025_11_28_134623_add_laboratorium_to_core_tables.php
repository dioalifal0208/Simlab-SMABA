<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Items
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'laboratorium')) {
                $table->string('laboratorium', 50)->default('Biologi')->after('user_id');
            }
        });

        // Loans
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'laboratorium')) {
                $table->string('laboratorium', 50)->default('Biologi')->after('user_id');
            }
        });

        // Item Requests
        Schema::table('item_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('item_requests', 'laboratorium')) {
                $table->string('laboratorium', 50)->default('Biologi')->after('user_id');
            }
        });

        // Pastikan data eksisting punya nilai default
        DB::table('items')->whereNull('laboratorium')->update(['laboratorium' => 'Biologi']);
        DB::table('loans')->whereNull('laboratorium')->update(['laboratorium' => 'Biologi']);
        DB::table('item_requests')->whereNull('laboratorium')->update(['laboratorium' => 'Biologi']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'laboratorium')) {
                $table->dropColumn('laboratorium');
            }
        });
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'laboratorium')) {
                $table->dropColumn('laboratorium');
            }
        });
        Schema::table('item_requests', function (Blueprint $table) {
            if (Schema::hasColumn('item_requests', 'laboratorium')) {
                $table->dropColumn('laboratorium');
            }
        });
    }
};
