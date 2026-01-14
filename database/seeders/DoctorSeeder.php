<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Aris Setiawan, Sp.PD', 'specialization' => 'Spesialis Penyakit Dalam'],
            ['name' => 'Dr. Bunga Citra, Sp.A', 'specialization' => 'Spesialis Anak'],
            ['name' => 'Dr. Chandra Wijaya, Sp.OG', 'specialization' => 'Spesialis Kandungan'],
            ['name' => 'Dr. Diana Putri, Sp.THT', 'specialization' => 'Spesialis THT'],
            ['name' => 'Dr. Eko Prasetyo, Sp.JP', 'specialization' => 'Spesialis Jantung'],
            ['name' => 'Dr. Fanya Lestari, Sp.KK', 'specialization' => 'Spesialis Kulit & Kelamin'],
            ['name' => 'Dr. Gunawan Toto, Sp.B', 'specialization' => 'Spesialis Bedah'],
            ['name' => 'Dr. Hany Safitri, Sp.M', 'specialization' => 'Spesialis Mata'],
            ['name' => 'Dr. Indra Kusuma, Sp.S', 'specialization' => 'Spesialis Saraf'],
            ['name' => 'Dr. Jihan Aurelia, Sp.GZ', 'specialization' => 'Spesialis Gizi Klinik'],
        ];

        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}