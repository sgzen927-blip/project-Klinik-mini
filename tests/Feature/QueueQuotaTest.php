<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Queue;
use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueQuotaTest extends TestCase
{
    use RefreshDatabase; // Membersihkan database setiap kali test dijalankan

    public function test_cannot_register_if_quota_20_is_full()
    {
        // 1. Persiapan Data (Setup)
        $user = User::factory()->create(['role' => 'user']);
        $poli = Poli::create(['name' => 'Umum']);
        $doctor = Doctor::create([
            'poli_id' => $poli->id,
            'name' => 'dr. Smith',
            'schedule_day' => 'Monday',
            'start_time' => '08:00',
            'end_time' => '12:00'
        ]);

        // 2. Simulasi kuota penuh (20 antrian sudah terdaftar)
        Queue::factory()->count(20)->create([
            'doctor_id' => $doctor->id, 
            'visit_date' => '2026-12-30',
            'status' => 'WAITING'
        ]);

        // 3. Eksekusi: Mencoba daftar sebagai pasien ke-21
        // Gunakan rute 'queue.store' agar sinkron dengan web.php sebelumnya
        $response = $this->actingAs($user)->post(route('queue.store'), [
            'doctor_id' => $doctor->id,
            'visit_date' => '2026-12-30',
            'complaint' => 'Sakit tenggorokan parah' // Minimal 10 karakter
        ]);

        // 4. Verifikasi (Assert)
        // Memastikan sistem menolak dan mengembalikan error pada input doctor_id
        $response->assertSessionHasErrors('doctor_id');
        
        // Memastikan jumlah data di database tetap 20 (tidak bertambah jadi 21)
        $this->assertEquals(20, Queue::where('doctor_id', $doctor->id)->count());
    }
}