<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan indexes untuk meningkatkan performa query pada tabel-tabel utama.
     */
    public function up(): void
    {
        // ========================================
        // ITEMS TABLE - Indexes untuk filtering dan sorting
        // ========================================
        Schema::table('items', function (Blueprint $table) {
            // Index untuk kolom yang sering di-filter (dengan pengecekan)
            if (Schema::hasColumn('items', 'laboratorium')) {
                $table->index('laboratorium', 'idx_items_laboratorium');
            }
            if (Schema::hasColumn('items', 'kondisi')) {
                $table->index('kondisi', 'idx_items_kondisi');
            }
            if (Schema::hasColumn('items', 'tipe')) {
                $table->index('tipe', 'idx_items_tipe');
            }
            
            // Composite index untuk filter kombinasi (lab + kondisi)
            if (Schema::hasColumn('items', 'laboratorium') && Schema::hasColumn('items', 'kondisi')) {
                $table->index(['laboratorium', 'kondisi'], 'idx_items_lab_kondisi');
            }
            
            // Index untuk sorting
            if (Schema::hasColumn('items', 'created_at')) {
                $table->index('created_at', 'idx_items_created_at');
            }
        });

        // ========================================
        // LOANS TABLE - Indexes untuk peminjaman
        // ========================================
        Schema::table('loans', function (Blueprint $table) {
            // Index untuk kolom yang sering di-filter
            $table->index('status', 'idx_loans_status');
            $table->index('laboratorium', 'idx_loans_laboratorium');
            
            // Index untuk tanggal (sorting dan filtering)
            $table->index('tanggal_pinjam', 'idx_loans_tanggal_pinjam');
            $table->index('tanggal_estimasi_kembali', 'idx_loans_tanggal_kembali');
            
            // Composite index untuk query dashboard admin
            // Query seperti: WHERE status = 'pending' AND user_id = X
            $table->index(['status', 'user_id'], 'idx_loans_status_user');
        });

        // ========================================
        // BOOKINGS TABLE - Indexes untuk booking lab
        // ========================================
        Schema::table('bookings', function (Blueprint $table) {
            // Index untuk kolom yang sering di-filter
            $table->index('status', 'idx_bookings_status');
            $table->index('laboratorium', 'idx_bookings_laboratorium');
            
            // Index untuk waktu (conflict detection & sorting)
            $table->index('waktu_mulai', 'idx_bookings_waktu_mulai');
            $table->index('waktu_selesai', 'idx_bookings_waktu_selesai');
            
            // Composite index untuk conflict checking
            // Query: WHERE laboratorium = X AND status = 'approved' 
            //        AND waktu_mulai < Y AND waktu_selesai > Z
            $table->index(['laboratorium', 'status', 'waktu_mulai'], 'idx_bookings_conflict');
        });

        // ========================================
        // DAMAGE_REPORTS TABLE - Indexes untuk laporan kerusakan
        // ========================================
        Schema::table('damage_reports', function (Blueprint $table) {
            // Index untuk status filtering
            $table->index('status', 'idx_damage_reports_status');
            
            // Index untuk sorting
            $table->index('created_at', 'idx_damage_reports_created_at');
        });

        // ========================================
        // DOCUMENTS TABLE - Indexes untuk pustaka digital
        // ========================================
        Schema::table('documents', function (Blueprint $table) {
            // Index untuk kategori filtering
            $table->index('kategori', 'idx_documents_kategori');
            
            // Index untuk sorting
            $table->index('created_at', 'idx_documents_created_at');
        });

        // ========================================
        // ITEM_REQUESTS TABLE - Indexes untuk permintaan item
        // ========================================
        Schema::table('item_requests', function (Blueprint $table) {
            // Index untuk status filtering
            $table->index('status', 'idx_item_requests_status');
            $table->index('laboratorium', 'idx_item_requests_laboratorium');
            
            // Index untuk sorting
            $table->index('created_at', 'idx_item_requests_created_at');
        });

        // ========================================
        // STOCK_REQUESTS TABLE - Indexes untuk permintaan stok
        // ========================================
        Schema::table('stock_requests', function (Blueprint $table) {
            // Index untuk status filtering
            $table->index('status', 'idx_stock_requests_status');
        });

        // ========================================
        // PRACTICUM_MODULES TABLE - Indexes untuk modul praktikum
        // ========================================
        Schema::table('practicum_modules', function (Blueprint $table) {
            // Index untuk sorting
            $table->index('created_at', 'idx_practicum_modules_created_at');
        });

        // ========================================
        // MAINTENANCE_LOGS TABLE - Indexes untuk log perawatan
        // ========================================
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Index untuk tanggal (sorting)
            $table->index('tanggal_perawatan', 'idx_maintenance_logs_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus semua indexes yang ditambahkan.
     */
    public function down(): void
    {
        // Items
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('idx_items_laboratorium');
            $table->dropIndex('idx_items_kondisi');
            $table->dropIndex('idx_items_tipe');
            $table->dropIndex('idx_items_lab_kondisi');
            $table->dropIndex('idx_items_created_at');
        });

        // Loans
        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex('idx_loans_status');
            $table->dropIndex('idx_loans_laboratorium');
            $table->dropIndex('idx_loans_tanggal_pinjam');
            $table->dropIndex('idx_loans_tanggal_kembali');
            $table->dropIndex('idx_loans_status_user');
        });

        // Bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_status');
            $table->dropIndex('idx_bookings_laboratorium');
            $table->dropIndex('idx_bookings_waktu_mulai');
            $table->dropIndex('idx_bookings_waktu_selesai');
            $table->dropIndex('idx_bookings_conflict');
        });

        // Damage Reports
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->dropIndex('idx_damage_reports_status');
            $table->dropIndex('idx_damage_reports_created_at');
        });

        // Documents
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('idx_documents_kategori');
            $table->dropIndex('idx_documents_created_at');
        });

        // Item Requests
        Schema::table('item_requests', function (Blueprint $table) {
            $table->dropIndex('idx_item_requests_status');
            $table->dropIndex('idx_item_requests_laboratorium');
            $table->dropIndex('idx_item_requests_created_at');
        });

        // Stock Requests
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropIndex('idx_stock_requests_status');
        });

        // Practicum Modules
        Schema::table('practicum_modules', function (Blueprint $table) {
            $table->dropIndex('idx_practicum_modules_created_at');
        });

        // Maintenance Logs
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->dropIndex('idx_maintenance_logs_tanggal');
        });
    }
};

