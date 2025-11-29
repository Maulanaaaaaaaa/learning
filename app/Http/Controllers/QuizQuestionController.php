<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class QuizQuestionController extends Controller
{


    public function submit(Request $request, $id_quiz)
{
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
    }

    $idMahasiswa = Auth::user()->mahasiswa->id;

    $attempt = \App\Models\QuizAttempt::where('id_quiz', $id_quiz)
        ->where('id_mahasiswa', $idMahasiswa)
        ->latest()
        ->first();

    if (!$attempt) {
        return redirect()->route('quizzes.mahasiswa', $id_quiz)->withErrors('Tidak ada attempt aktif.');
    }

    // Pastikan jawaban tidak null
    $jawabanUser = $request->jawaban ?? [];

    foreach ($jawabanUser as $id_question => $answer) {
        $question = \App\Models\QuizQuestion::find($id_question);
    
        if (!$question) {
            continue;
        }
    
        $isCorrect = null;
        $nilai = null;
    
        if ($question->jenis_pertanyaan == 'pilihan_ganda') {
            // Jawaban benar atau tidak
            $isCorrect = (strtolower($answer) === strtolower($question->jawaban_benar));
            $nilai = $isCorrect ? $question->bobot_nilai : 0;
        } elseif ($question->jenis_pertanyaan == 'esay') {
            // Esai mungkin dinilai manual
            $isCorrect = null;
            $nilai = null;
        }

        // Simpan jawaban meskipun kosong
        \App\Models\QuizAnswer::create([
            'id_attempt' => $attempt->id,
            'id_question' => $id_question,
            'opsi' => $question->jenis_pertanyaan == 'pilihan_ganda' ? $answer : null,
            'jawaban_teks' => $question->jenis_pertanyaan == 'esay' ? $answer : null,
            'is_correct' => $isCorrect,
            'nilai' => $nilai,
        ]);
    }

    // Tandai bahwa quiz telah selesai
    $attempt->update(['ended_at' => now()]);
    app(QuizAttemptController::class)->updateTotalScore($attempt->id);

    return redirect()->route('quizzes.mahasiswa', $id_quiz)->with('success', 'Jawaban berhasil dikirim dan quiz telah selesai.');
}




    public function indexFern($id_quiz)
    {
        $quiz = Quiz::findOrFail($id_quiz);
        $questions = QuizQuestion::where('id_quiz', $id_quiz)->orderBy('urutan')->get();

        foreach ($questions as $question) {
            $question->opsi_jawaban = $question->opsi_jawaban ? json_decode($question->opsi_jawaban, true) : [];
        }

        return view('assignments.fern', compact('quiz', 'questions'));
    }

    public function index($id_quiz)
    {
        $quiz = Quiz::findOrFail($id_quiz);
        $questions = QuizQuestion::where('id_quiz', $id_quiz)->orderBy('urutan')->get();

        // Pastikan opsi_jawaban dikonversi menjadi array jika ada
        foreach ($questions as $question) {
            $question->opsi_jawaban = $question->opsi_jawaban ? json_decode($question->opsi_jawaban, true) : [];
        }

        return view('assignments.questions', compact('quiz', 'questions'));
    }


    public function store(Request $request, $id_quiz)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'jenis_pertanyaan' => 'required|in:pilihan_ganda,esay',
            'opsi_jawaban' => 'nullable|array',
            'jawaban_benar' => 'nullable|string|required_if:jenis_pertanyaan,pilihan_ganda',
            'bobot_nilai' => 'required|integer|min:1',
        ]);

        QuizQuestion::create([
            'id_quiz' => $id_quiz,
            'pertanyaan' => $request->pertanyaan,
            'jenis_pertanyaan' => $request->jenis_pertanyaan,
            'opsi_jawaban' => $request->jenis_pertanyaan === 'pilihan_ganda' ? json_encode($request->opsi_jawaban) : null,
            'jawaban_benar' => $request->jenis_pertanyaan === 'pilihan_ganda' ? $request->jawaban_benar : null,

            'bobot_nilai' => $request->bobot_nilai,
            'urutan' => QuizQuestion::where('id_quiz', $id_quiz)->count() + 1,
        ]);

        return redirect()->route('quiz_questions.index', $id_quiz)->with('success', 'Soal berhasil ditambahkan');
    }

    public function edit($id_quiz, $id_question)
    {
        $quiz = Quiz::findOrFail($id_quiz);
        $question = QuizQuestion::findOrFail($id_question);
        return view('quiz_questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, $id_quiz, $id_question)
    {
        $request->validate([
            'pertanyaan' => 'required',
            'jenis_pertanyaan' => 'required|in:pilihan_ganda,esay',
            'opsi_jawaban' => 'nullable|array',
            'jawaban_benar' => 'nullable|string|required_if:jenis_pertanyaan,pilihan_ganda',

            'bobot_nilai' => 'required|integer|min:1',
        ]);

        $question = QuizQuestion::findOrFail($id_question);
        $question->update([
            'pertanyaan' => $request->pertanyaan,
            'jenis_pertanyaan' => $request->jenis_pertanyaan,
            'opsi_jawaban' => $request->jenis_pertanyaan === 'pilihan_ganda' ? json_encode($request->opsi_jawaban) : null,
            'jawaban_benar' => $request->jenis_pertanyaan === 'pilihan_ganda' ? $request->jawaban_benar : null,

            'bobot_nilai' => $request->bobot_nilai,
        ]);

        return redirect()->route('quiz_questions.index', $id_quiz)->with('success', 'Soal berhasil diperbarui');
    }

    public function destroy($id_quiz, $id_question)
    {
        $question = QuizQuestion::findOrFail($id_question);
        $question->delete();

        return redirect()->route('quiz_questions.index', $id_quiz)->with('success', 'Soal berhasil dihapus');
    }
}
