<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matakuliah extends Model
{
    use HasFactory;

    // Nama tabel yang terkait
    protected $table = 'matakuliahs';

    // Primary Key
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'id_prodi',
        'id_admin',
        'id_dosen',
        'kode_mk',
        'nama_matakuliah',
        'sks',
        'deskripsi',
        'status_persetujuan',
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    public function room()
    {
        return $this->hasMany(Room::class, 'id_matakuliah');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'enrolments', 'id_matakuliah', 'id_mahasiswa');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'id_matakuliah');
    }

    public function enrolments()
    {
        return $this->hasMany(Enrolment::class, 'id_matakuliah');
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'id_matakuliah');
    }
}
