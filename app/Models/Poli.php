<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $fillable = ['name']; // [cite: 49]

    // Relasi: Satu Poli memiliki banyak Dokter 
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}