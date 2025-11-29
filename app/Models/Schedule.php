<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'schedules';

    protected $fillable = [
        'id_room',
        'id_matakuliah', // Tambahkan ini
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'hari'
    ];

    // Relasi ke Room (Kelas)
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }

    public function assignments()
{
    return $this->hasMany(Assignment::class, 'id_schedule');
}

public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah');
    }
    
    
}
