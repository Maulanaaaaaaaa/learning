<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function updateTotalScore($id_attempt)
{
    $attempt = QuizAttempt::findOrFail($id_attempt);

    // Ambil total nilai dari semua jawaban mahasiswa dalam attempt ini
    $totalScore = \App\Models\QuizAnswer::where('id_attempt', $id_attempt)->sum('nilai');

    // Update total_score di quiz_attempts
    $attempt->update(['total_score' => $totalScore]);

    return back()->with('success', 'Total skor berhasil diperbarui!');
}

    public function index($id_quiz)
    {
        $attempts = QuizAttempt::with(['quiz', 'mahasiswa'])
            ->where('id_quiz', $id_quiz)
            ->get();
    
        return view('assignments.attempts', compact('attempts', 'id_quiz'));
    }
    
    public function store(Request $request, $id_quiz)
{
    $request->validate([
        'id_mahasiswa' => 'required|exists:mahasiswas,id',
    ]);

    // Ambil data quiz dari tabel quizzes
    $quiz = \App\Models\Quiz::findOrFail($id_quiz);
    $waktuMulai = \Carbon\Carbon::parse($quiz->waktu_pengerjaan); // Gunakan waktu_pengerjaan dari quiz
    $durasi = $quiz->durasi; // Ambil durasi dari database

    // Hitung attempt ke-berapa
    $lastAttempt = QuizAttempt::where('id_quiz', $id_quiz)
        ->where('id_mahasiswa', $request->id_mahasiswa)
        ->orderBy('attempt_number', 'desc')
        ->first();
    $newAttemptNumber = $lastAttempt ? $lastAttempt->attempt_number + 1 : 1;

    // Simpan attempt baru dengan waktu mulai dari quiz dan waktu selesai berdasarkan durasi quiz
    $attempt = QuizAttempt::create([
        'id_quiz' => $id_quiz,
        'id_mahasiswa' => $request->id_mahasiswa,
        'attempt_number' => $newAttemptNumber,
        'total_score' => 0,
        'started_at' => $waktuMulai, // Menggunakan waktu_pengerjaan dari Quiz
        'ended_at' => $waktuMulai->copy()->addMinutes($durasi), // Menghitung waktu selesai
    ]);

    return redirect()->route('attempts.index', $id_quiz)->with('success', 'Percobaan quiz berhasil dimulai!');
}

    

    
    public function edit($id_quiz, $id)
    {
        $attempt = QuizAttempt::where('id_quiz', $id_quiz)->findOrFail($id);
        return view('assignments.edit_attempt', compact('attempt', 'id_quiz'));
    }
    
    public function update(Request $request, $id_quiz, $id)
{
    $attempt = QuizAttempt::where('id_quiz', $id_quiz)->findOrFail($id);

    if (now()->greaterThan($attempt->ended_at)) {
        return redirect()->route('attempts.index', $id_quiz)->with('error', 'Waktu pengerjaan telah habis!');
    }

    $request->validate([
        'total_score' => 'nullable|integer|min:0',
        'ended_at' => 'nullable|date',
    ]);

    $attempt->update([
        'total_score' => $request->total_score ?? $attempt->total_score,
        'ended_at' => $request->ended_at ?? now(),
    ]);

    return redirect()->route('attempts.index', $id_quiz)->with('success', 'Percobaan quiz berhasil diperbarui!');
}



    
    public function destroy($id_quiz, $id)
    {
        $attempt = QuizAttempt::where('id_quiz', $id_quiz)->findOrFail($id);
        $attempt->delete();
    
        return redirect()->route('attempts.index', $id_quiz)->with('success', 'Percobaan quiz berhasil dihapus!');
    }
}
