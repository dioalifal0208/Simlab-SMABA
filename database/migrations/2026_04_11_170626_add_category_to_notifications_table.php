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
        Schema::table('notifications', function (Blueprint $table) {
            // Add a virtual/generated column to extract 'category' from JSON data
            // This allows indexed querying without manual JSON parsing in each query
            $table->string('category', 50)
                ->virtualAs('data->>"$.category"')
                ->nullable()
                ->after('type')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }
};
