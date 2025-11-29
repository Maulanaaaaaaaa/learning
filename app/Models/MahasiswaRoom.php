<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaRoom extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa_rooms';

    protected $fillable = [
        'id_mahasiswa',
        'id_room'
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
