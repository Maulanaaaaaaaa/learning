<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis'; 
    protected $fillable = ['id_admin','kode_prodi', 'nama_prodi'];

    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'id_prodi');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function kelas()
    {
        return $this->hasMany(Kelass::class, 'id_prodi');
    }
}
