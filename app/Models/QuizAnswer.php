<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_answers'; // Nama tabel di database

    protected $fillable = [
        'id_attempt',
        'id_question',
        'opsi',
        'jawaban_teks',
        'is_correct',
        'nilai',
    ];

    /**
     * Relasi ke tabel quiz_attempts
     */
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'id_attempt');
    }

    /**
     * Relasi ke tabel quiz_questions
     */
    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'id_question');
    }
}
