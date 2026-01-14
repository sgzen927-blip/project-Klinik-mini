<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class AdminQueueController extends Controller
{
    /**
     * Menampilkan Dashboard Admin dengan daftar antrian hari ini.
     */
    public function dashboard()
    {
        // Mengambil antrian khusus hari ini agar dashboard tetap rapi
        $queues = Queue::with(['user', 'doctor'])
            ->whereDate('visit_date', date('Y-m-d')) 
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('admin.dashboard', compact('queues'));
    }

    /**
     * Logika untuk memanggil antrian berdasarkan Dokter.
     */
    public function callNext($doctorId)
    {
        // Mencari antrian pertama yang masih 'WAITING' untuk dokter tersebut hari ini
        $nextQueue = Queue::where('doctor_id', $doctorId)
            ->whereDate('visit_date', date('Y-m-d'))
            ->where('status', 'WAITING')
            ->orderBy('queue_number', 'asc')
            ->first();

        if ($nextQueue) {
            $nextQueue->update(['status' => 'CALLED']);
            return back()->with('success', 'Antrian #' . $nextQueue->queue_number . ' dipanggil!');
        }

        return back()->with('error', 'Tidak ada antrian menunggu untuk dokter ini.');
    }

    /**
     * Mengupdate status antrian secara spesifik (digunakan oleh form PATCH).
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        // Validasi input status agar hanya menerima nilai yang diizinkan
        $request->validate([
            'status' => 'required|in:WAITING,CALLED,CANCELLED'
        ]);

        $queue->update([
            'status' => $request->status
        ]);

        $pesan = $request->status == 'CALLED' ? 'Pasien dipanggil.' : 'Status diperbarui.';
        return back()->with('success', $pesan);
    }
}