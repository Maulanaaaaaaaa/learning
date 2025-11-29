<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';
    protected $fillable = [
        'id_assignment', 'judul', 'deskripsi', 'jenis_soal', 'waktu_pengerjaan', 'durasi', 'quiz_password', 'attempt_limit'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'id_assignment');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'id_quiz');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'id_quiz');
    }

    
}
