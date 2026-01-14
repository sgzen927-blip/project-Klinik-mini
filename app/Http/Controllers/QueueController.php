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
        $queues = Queue::where('user_id', Auth::id())->with('doctor')->get();
        return view('queue.index', compact('queues'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'complaint' => 'required|string|min:10',
        ]);

        // 2. SOLUSI ERROR image_801109.png: Cek apakah user sudah daftar
        $exists = Queue::where('user_id', Auth::id())
            ->where('doctor_id', $request->doctor_id)
            ->where('visit_date', $request->visit_date)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['doctor_id' => 'Anda sudah terdaftar di dokter ini pada tanggal tersebut.']);
        }

        // 3. Hitung nomor antrian selanjutnya
        $lastQueue = Queue::where('doctor_id', $request->doctor_id)
            ->where('visit_date', $request->visit_date)
            ->max('queue_number');

        $newNumber = ($lastQueue ?? 0) + 1;

        // 4. Simpan data
        Queue::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'queue_number' => $newNumber,
            'visit_date' => $request->visit_date,
            'complaint' => $request->complaint,
            'status' => 'WAITING',
        ]);

        return redirect()->route('queue.index')->with('success', 'Pendaftaran berhasil! Nomor antrian Anda: #' . $newNumber);
    }
}