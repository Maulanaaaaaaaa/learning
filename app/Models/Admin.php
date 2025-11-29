<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'no_telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'id_admin');
    }

    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'id_prodi');
    }

    public function kelass()
{
    return $this->hasMany(Kelass::class, 'id_admin');
}

}
