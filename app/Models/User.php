<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nim',
        'nidn',
        'nama',
        'email',
        'no_telp',
        'password',
        'role',
        'status_akun',
        'is_online',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Relasi ke tabel admin
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    /**
     * Relasi ke tabel dosen
     */
    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_user');
    }

    /**
     * Relasi ke tabel mahasiswa
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    public function matakuliahs()
    {
        return $this->belongsToMany(Matakuliah::class, 'enrolments', 'id_mahasiswa', 'id_matakuliah');
    }

    public function enrolments()
{
    return $this->hasMany(Enrolment::class, 'id_mahasiswa');
}

}
