<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{
    use HasFactory;

    protected $table = 'enrolments';
    protected $fillable = ['id_mahasiswa', 'id_matakuliah', 'status'];

    // Relasi ke Mahasiswa
    public function mahasiswa()
{
    return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
}


    // Relasi ke Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah');
    }
   
}
