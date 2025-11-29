<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';
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
        return $this->hasMany(Matakuliah::class, 'id_dosen');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'id_dosen');
    }
}
