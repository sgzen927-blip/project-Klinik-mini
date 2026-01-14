<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk memperbaiki tabel queues.
     */
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // 1. Tambahkan kolom patient_name jika belum ada
            if (!Schema::hasColumn('queues', 'patient_name')) {
                $table->string('patient_name')->after('user_id')->nullable();
            }

            // 2. Hapus Unique Constraint secara aman
            // Menggunakan cara manual via DB::select untuk memastikan index ada sebelum di-drop
            $conn = Schema::getConnection();
            $dbName = $conn->getDatabaseName();
            
            $indexExists = DB::select("
                SELECT INDEX_NAME 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'queues' 
                AND INDEX_NAME = 'queues_unique_registration'
            ", [$dbName]);

            if (!empty($indexExists)) {
                $table->dropUnique('queues_unique_registration');
            }
        });
    }

    /**
     * Kembalikan perubahan jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            // Cek sebelum menambahkan kembali agar tidak error saat rollback
            $table->unique(['user_id', 'doctor_id', 'visit_date'], 'queues_unique_registration');

            if (Schema::hasColumn('queues', 'patient_name')) {
                $table->dropColumn('patient_name');
            }
        });
    }
};