<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'user_id', 'doctor_id', 'visit_date', 
        'queue_number', 'complaint', 'status'
    ]; // [cite: 53, 54, 56, 57, 61]

    // Relasi: Antrian ini dimiliki oleh satu User (Pasien) [cite: 7]
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Antrian ini ditujukan untuk satu Dokter [cite: 53]
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}