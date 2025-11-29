<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelass extends Model
{
    use HasFactory;

    protected $table = 'kelass';
    protected $primaryKey = 'id'; // Pastikan ini benar

    protected $fillable = [
        'id_admin',
        'id_prodi',
        'nama_kelas',
        'semester',
        'kode_kelas',
        'jenis_kelas',
    ];

    /**
     * Relasi ke model Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_kelas', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }
}
