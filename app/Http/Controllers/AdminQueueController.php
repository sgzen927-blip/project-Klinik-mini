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
        // Mengambil antrian khusus hari ini.
        // Diurutkan berdasarkan queue_number agar konsisten di admin dan user.
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
     * Mengupdate status antrian secara spesifik.
     * Mendukung status: WAITING, CALLED, DONE, CANCELLED.
     */
    public function updateStatus(Request $request, Queue $queue)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:WAITING,CALLED,DONE,CANCELLED'
        ]);

        $queue->update([
            'status' => $request->status
        ]);

        // Penentuan pesan notifikasi berdasarkan status baru
        switch ($request->status) {
            case 'CALLED':
                $pesan = 'Pasien #' . $queue->queue_number . ' sedang dipanggil.';
                break;
            case 'DONE':
                $pesan = 'Pasien #' . $queue->queue_number . ' telah selesai diperiksa.';
                break;
            case 'CANCELLED':
                $pesan = 'Antrian #' . $queue->queue_number . ' telah dibatalkan.';
                break;
            default:
                $pesan = 'Status antrian #' . $queue->queue_number . ' berhasil diperbarui.';
                break;
        }

        return back()->with('success', $pesan);
    }
}