<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'rooms';
    protected $fillable = [
        'id_matakuliah', // Kolom untuk relasi ke tabel matakuliah
        'id_kelas',      // Kolom untuk relasi ke tabel kelass
        
        'nama_ruangan',  // Nama ruang kelas (bisa null jika online)
        'jenis_kelas',   // Jenis kelas (offline/online)
    ];

    // Relasi ke Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah');
    }

    // Relasi ke Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_room');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'id_room');
    }

    public function mahasiswa()
{
    return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_rooms', 'id_room', 'id_mahasiswa');
}
public function kelas()
{
    return $this->belongsTo(Kelass::class, 'id_kelas', 'id');
}


}
