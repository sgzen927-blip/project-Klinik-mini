<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Penting: Tambahkan ini agar model User dikenali
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

{
    $this->call([
        DoctorSeeder::class,
        // Seeder lainnya jika ada
    ]);
}
        // Pembuatan Akun Admin sesuai ketentuan soal [cite: 7, 79]
        User::create([
            'name' => 'Admin Clinic',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Pembuatan Akun User sesuai ketentuan soal [cite: 7, 80]
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}