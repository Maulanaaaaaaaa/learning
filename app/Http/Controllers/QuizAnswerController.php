<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizAnswerController extends Controller
{
    public function index()
    {
        // Mengambil semua answers beserta pertanyaannya
        $answers = QuizAnswer::with('question')->get();

        return view('assignments.chika', compact('answers'));
    }

    public function updateNilaiEsai(Request $request, $id_answer)
{
    $answers = QuizAnswer::findOrFail($id_answer);
    $question = QuizQuestion::findOrFail($answers->id_question);
    $maxNilai = $question->bobot_nilai;

    $validator = Validator::make($request->all(), [
        'nilai' => "required|integer|min:0|max:$maxNilai",
    ]);

    if ($validator->fails()) {
        return back()->withErrors(['error' => "Nilai harus antara 0-$maxNilai."]);
    }

    $answers->update(['nilai' => $request->nilai]);

    // Tambahkan update total_score setelah nilai diperbarui
    app(QuizAttemptController::class)->updateTotalScore($answers->id_attempt);

    return back()->with('success', 'Nilai berhasil diperbarui!');
}


}
