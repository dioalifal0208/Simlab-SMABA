<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'laboratorium')) {
                $table->string('laboratorium', 50)->nullable()->after('role');
            }
        });

        // Seed data
        $mapping = [
            'gurubiologi@smaba.sch.id' => 'Biologi',
            'gurufisika@smaba.sch.id' => 'Fisika',
            'gurubahasa@smaba.sch.id' => 'Bahasa',
        ];

        foreach ($mapping as $email => $lab) {
            DB::table('users')
                ->where('email', $email)
                ->update(['laboratorium' => $lab]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'laboratorium')) {
                $table->dropColumn('laboratorium');
            }
        });
    }
};
