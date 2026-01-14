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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            // Menambahkan onDelete('cascade') agar data bersih jika user/dokter dihapus
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade'); 
            $table->date('visit_date'); 
            $table->integer('queue_number'); 
            $table->text('complaint'); 
            
            // Status antrian sesuai ketentuan
            $table->enum('status', ['WAITING', 'CALLED', 'DONE', 'CANCELED'])->default('WAITING');
            $table->timestamps();

            // Aturan Bisnis: Satu pasien hanya bisa daftar 1x di dokter & tanggal yang sama
            $table->unique(['user_id', 'doctor_id', 'visit_date'], 'unique_registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};