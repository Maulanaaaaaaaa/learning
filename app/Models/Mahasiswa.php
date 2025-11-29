<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';
    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'nama', 'email', 'no_telp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function enrolments()
    {
        return $this->hasMany(Enrolment::class, 'id_mahasiswa');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'id_mahasiswa');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'id_mahasiswa');
    }

    public function rooms()
{
    return $this->belongsToMany(Room::class, 'mahasiswa_rooms', 'id_mahasiswa', 'id_room');
}

}

