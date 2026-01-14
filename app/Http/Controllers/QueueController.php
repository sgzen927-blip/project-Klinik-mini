<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function create()
    {
        $doctors = Doctor::all();
        return view('queue.create', compact('doctors'));
    }

    public function index()
    {
        // Menampilkan riwayat milik user yang login
        $queues = Queue::where('user_id', Auth::id())
            ->with('doctor')
            ->orderBy('visit_date', 'desc')
            ->orderBy('queue_number', 'asc')
            ->get();
            
        return view('queue.index', compact('queues'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'doctor_id'    => 'required|exists:doctors,id',
            'visit_date'   => 'required|date|after_or_equal:today',
            'complaint'    => 'required|string|min:10',
        ]);

        // 2. Cek apakah NAMA PASIEN ini sudah terdaftar di dokter & tanggal yang sama
        // Hal ini untuk mencegah double input yang tidak sengaja (klik tombol 2x)
        $exists = Queue::where('patient_name', $request->patient_name)
            ->where('doctor_id', $request->doctor_id)
            ->where('visit_date', $request->visit_date)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['patient_name' => 'Pasien ini sudah terdaftar pada dokter dan tanggal yang dipilih.']);
        }

        // 3. Logika Nomor Antrean Otomatis Berurutan (Per Dokter & Per Tanggal)
        $lastQueue = Queue::where('doctor_id', $request->doctor_id)
            ->where('visit_date', $request->visit_date)
            ->max('queue_number');

        $newNumber = ($lastQueue ?? 0) + 1;

        // 4. Eksekusi Simpan
        Queue::create([
            'user_id'      => Auth::id(),
            'patient_name' => $request->patient_name,
            'doctor_id'    => $request->doctor_id,
            'queue_number' => $newNumber,
            'visit_date'   => $request->visit_date,
            'complaint'    => $request->complaint,
            'status'       => 'WAITING',
        ]);

        return redirect()->route('queue.index')
            ->with('success', "Pendaftaran berhasil! Pasien: {$request->patient_name} mendapat No. Antrean: #{$newNumber}");
    }
}