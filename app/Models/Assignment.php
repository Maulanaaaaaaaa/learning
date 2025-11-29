<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'id_dosen',
        'id_room',
        'id_matakuliah', 
        'id_schedule',       
        'judul',
        'deskripsi',
        'file',
        'jenis_tugas',
        'deadline',
        'original_name',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
    
    // Relasi ke Room (Kelas)
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_matakuliah');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'id_assignment');
    }
    
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'id_assignment');
    }

    public function schedule()
{
    return $this->belongsTo(Schedule::class, 'id_schedule');
}


}
