<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';
    protected $fillable = ['id_quiz', 'pertanyaan', 'jenis_pertanyaan', 'opsi_jawaban', 'jawaban_benar', 'bobot_nilai', 'urutan'];

    protected $casts = [
        'opsi_jawaban' => 'array' // Menyimpan opsi dalam format JSON
    ];

    public function quiz()
{
    return $this->belongsTo(Quiz::class, 'id_quiz'); 
}


    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'id_question');
    }
}

