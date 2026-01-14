<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Queue; // Penting: Import model Queue agar tidak error
use Illuminate\Support\Facades\Auth;

class StoreQueueRequest extends FormRequest
{
    /**
     * Tentukan apakah user diperbolehkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Hanya user yang sudah login bisa mendaftar
    }

    /**
     * Aturan validasi dasar.
     */
    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'complaint' => 'required|string|min:10', // Keluhan minimal 10 karakter
        ];
    }

    /**
     * Konfigurasi validator untuk aturan bisnis tambahan.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Aturan 1: Maksimal 20 antrian per dokter per hari
            $count = Queue::where('doctor_id', $this->doctor_id)
                          ->where('visit_date', $this->visit_date)
                          ->whereIn('status', ['WAITING', 'CALLED', 'DONE']) // Hanya hitung yang aktif
                          ->count();

            if ($count >= 20) {
                $validator->errors()->add('doctor_id', 'Kuota dokter untuk tanggal ini sudah penuh (Maksimal 20).');
            }

            // Aturan 2: Tidak boleh daftar dua kali pada dokter & hari yang sama
            $exists = Queue::where('user_id', Auth::id())
                           ->where('doctor_id', $this->doctor_id)
                           ->where('visit_date', $this->visit_date)
                           ->where('status', '!=', 'CANCELED') // Abaikan jika sudah dibatalkan sebelumnya
                           ->exists();

            if ($exists) {
                $validator->errors()->add('doctor_id', 'Anda sudah terdaftar untuk dokter ini pada tanggal tersebut.');
            }
        });
    }
}