<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Assign lab ownership to the built-in guru accounts
     * so inventory filtering works as expected.
     */
    public function up(): void
    {
        $mapping = [
            'gurubiologi@smaba.sch.id' => 'Biologi',
            'gurufisika@smaba.sch.id' => 'Fisika',
            'gurubahasa@smaba.sch.id' => 'Bahasa',
        ];

        foreach ($mapping as $email => $lab) {
            DB::table('users')
                ->where('email', $email)
                ->whereNull('laboratorium')
                ->update(['laboratorium' => $lab]);
        }
    }

    /**
     * Roll back the hard-coded assignments for the sample guru accounts.
     */
    public function down(): void
    {
        $emails = [
            'gurubiologi@smaba.sch.id',
            'gurufisika@smaba.sch.id',
            'gurubahasa@smaba.sch.id',
        ];

        DB::table('users')
            ->whereIn('email', $emails)
            ->whereIn('laboratorium', ['Biologi', 'Fisika', 'Bahasa'])
            ->update(['laboratorium' => null]);
    }
};
