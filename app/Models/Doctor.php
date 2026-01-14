<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['poli_id', 'name', 'schedule_day', 'start_time', 'end_time']; // [cite: 50]

    // Relasi: Dokter ini termasuk dalam satu Poli 
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    // Relasi: Dokter memiliki banyak riwayat antrian 
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}