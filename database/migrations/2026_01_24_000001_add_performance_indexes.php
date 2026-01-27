<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan indexes untuk meningkatkan performa query pada tabel-tabel utama.
     * Semua index ditambahkan dengan pengecekan keberadaan kolom.
     */
    public function up(): void
    {
        // Helper function to safely add index
        $safeIndex = function (Blueprint $table, string $tableName, $columns, string $indexName) {
            $columnsArray = is_array($columns) ? $columns : [$columns];
            $allColumnsExist = true;
            foreach ($columnsArray as $column) {
                if (!Schema::hasColumn($tableName, $column)) {
                    $allColumnsExist = false;
                    break;
                }
            }
            if ($allColumnsExist) {
                $table->index($columns, $indexName);
            }
        };

        // ========================================
        // ITEMS TABLE - Indexes untuk filtering dan sorting
        // ========================================
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'items', 'laboratorium', 'idx_items_laboratorium');
                $safeIndex($table, 'items', 'kondisi', 'idx_items_kondisi');
                $safeIndex($table, 'items', 'tipe', 'idx_items_tipe');
                $safeIndex($table, 'items', ['laboratorium', 'kondisi'], 'idx_items_lab_kondisi');
                $safeIndex($table, 'items', 'created_at', 'idx_items_created_at');
            });
        }

        // ========================================
        // LOANS TABLE - Indexes untuk peminjaman
        // ========================================
        if (Schema::hasTable('loans')) {
            Schema::table('loans', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'loans', 'status', 'idx_loans_status');
                $safeIndex($table, 'loans', 'laboratorium', 'idx_loans_laboratorium');
                $safeIndex($table, 'loans', 'tanggal_pinjam', 'idx_loans_tanggal_pinjam');
                $safeIndex($table, 'loans', 'tanggal_estimasi_kembali', 'idx_loans_tanggal_kembali');
                $safeIndex($table, 'loans', ['status', 'user_id'], 'idx_loans_status_user');
            });
        }

        // ========================================
        // BOOKINGS TABLE - Indexes untuk booking lab
        // ========================================
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'bookings', 'status', 'idx_bookings_status');
                $safeIndex($table, 'bookings', 'laboratorium', 'idx_bookings_laboratorium');
                $safeIndex($table, 'bookings', 'waktu_mulai', 'idx_bookings_waktu_mulai');
                $safeIndex($table, 'bookings', 'waktu_selesai', 'idx_bookings_waktu_selesai');
                $safeIndex($table, 'bookings', ['laboratorium', 'status', 'waktu_mulai'], 'idx_bookings_conflict');
            });
        }

        // ========================================
        // DAMAGE_REPORTS TABLE - Indexes untuk laporan kerusakan
        // ========================================
        if (Schema::hasTable('damage_reports')) {
            Schema::table('damage_reports', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'damage_reports', 'status', 'idx_damage_reports_status');
                $safeIndex($table, 'damage_reports', 'created_at', 'idx_damage_reports_created_at');
            });
        }

        // ========================================
        // DOCUMENTS TABLE - Indexes untuk pustaka digital
        // ========================================
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'documents', 'kategori', 'idx_documents_kategori');
                $safeIndex($table, 'documents', 'created_at', 'idx_documents_created_at');
            });
        }

        // ========================================
        // ITEM_REQUESTS TABLE - Indexes untuk permintaan item
        // ========================================
        if (Schema::hasTable('item_requests')) {
            Schema::table('item_requests', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'item_requests', 'status', 'idx_item_requests_status');
                $safeIndex($table, 'item_requests', 'laboratorium', 'idx_item_requests_laboratorium');
                $safeIndex($table, 'item_requests', 'created_at', 'idx_item_requests_created_at');
            });
        }

        // ========================================
        // STOCK_REQUESTS TABLE - Indexes untuk permintaan stok
        // ========================================
        if (Schema::hasTable('stock_requests')) {
            Schema::table('stock_requests', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'stock_requests', 'status', 'idx_stock_requests_status');
            });
        }

        // ========================================
        // PRACTICUM_MODULES TABLE - Indexes untuk modul praktikum
        // ========================================
        if (Schema::hasTable('practicum_modules')) {
            Schema::table('practicum_modules', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'practicum_modules', 'created_at', 'idx_practicum_modules_created_at');
            });
        }

        // ========================================
        // MAINTENANCE_LOGS TABLE - Indexes untuk log perawatan
        // ========================================
        if (Schema::hasTable('maintenance_logs')) {
            Schema::table('maintenance_logs', function (Blueprint $table) use ($safeIndex) {
                $safeIndex($table, 'maintenance_logs', 'tanggal_perawatan', 'idx_maintenance_logs_tanggal');
            });
        }
    }

    /**
     * Reverse the migrations.
     * Menghapus semua indexes yang ditambahkan dengan aman.
     */
    public function down(): void
    {
        $safeDropIndex = function (string $tableName, string $indexName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                    try {
                        $table->dropIndex($indexName);
                    } catch (\Exception $e) {
                        // Index doesn't exist, skip
                    }
                });
            }
        };

        // Items
        $safeDropIndex('items', 'idx_items_laboratorium');
        $safeDropIndex('items', 'idx_items_kondisi');
        $safeDropIndex('items', 'idx_items_tipe');
        $safeDropIndex('items', 'idx_items_lab_kondisi');
        $safeDropIndex('items', 'idx_items_created_at');

        // Loans
        $safeDropIndex('loans', 'idx_loans_status');
        $safeDropIndex('loans', 'idx_loans_laboratorium');
        $safeDropIndex('loans', 'idx_loans_tanggal_pinjam');
        $safeDropIndex('loans', 'idx_loans_tanggal_kembali');
        $safeDropIndex('loans', 'idx_loans_status_user');

        // Bookings
        $safeDropIndex('bookings', 'idx_bookings_status');
        $safeDropIndex('bookings', 'idx_bookings_laboratorium');
        $safeDropIndex('bookings', 'idx_bookings_waktu_mulai');
        $safeDropIndex('bookings', 'idx_bookings_waktu_selesai');
        $safeDropIndex('bookings', 'idx_bookings_conflict');

        // Damage Reports
        $safeDropIndex('damage_reports', 'idx_damage_reports_status');
        $safeDropIndex('damage_reports', 'idx_damage_reports_created_at');

        // Documents
        $safeDropIndex('documents', 'idx_documents_kategori');
        $safeDropIndex('documents', 'idx_documents_created_at');

        // Item Requests
        $safeDropIndex('item_requests', 'idx_item_requests_status');
        $safeDropIndex('item_requests', 'idx_item_requests_laboratorium');
        $safeDropIndex('item_requests', 'idx_item_requests_created_at');

        // Stock Requests
        $safeDropIndex('stock_requests', 'idx_stock_requests_status');

        // Practicum Modules
        $safeDropIndex('practicum_modules', 'idx_practicum_modules_created_at');

        // Maintenance Logs
        $safeDropIndex('maintenance_logs', 'idx_maintenance_logs_tanggal');
    }
};
