<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{



    public function indexMahasiswa($id_quiz)
    {
        $quizzes = Quiz::with('assignment')->get();
        return view('assignments.frieren', compact('quizzes'));
    }

    public function verify(Request $request, Quiz $quiz)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }

        $idMahasiswa = Auth::user()->mahasiswa->id;

        $request->validate([
            'quiz_password' => 'required|string',
        ]);

        if (Hash::check($request->quiz_password, $quiz->quiz_password)) {
            // Cek jumlah attempt mahasiswa
            $totalAttempts = \App\Models\QuizAttempt::where('id_quiz', $quiz->id)
                ->where('id_mahasiswa', $idMahasiswa)
                ->count();

            if ($totalAttempts >= $quiz->attempt_limit) {
                return back()->withErrors(['quiz_password' => 'Anda telah mencapai batas attempt untuk quiz ini.']);
            }

            // Buat attempt baru
            $attempt = \App\Models\QuizAttempt::create([
                'id_quiz' => $quiz->id,
                'id_mahasiswa' => $idMahasiswa,
                'attempt_number' => $totalAttempts + 1,
                'total_score' => 0,
                'started_at' => now(),
                'ended_at' => now()->addMinutes($quiz->durasi),
            ]);

            return redirect()->route('quizmaha.index', [
                'id_quiz' => $quiz->id,
                'timer' => $attempt->ended_at->timestamp * 1000 // Kirim dalam format milidetik
            ]);
        }

        return back()->withErrors(['quiz_password' => 'Password salah!']);
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::with('assignment')->get();
        return view('assignments.quiz', compact('quizzes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_assignment' => [
                'required',
                'exists:assignments,id',
                function ($attribute, $value, $fail) {
                    $assignment = Assignment::find($value);
                    if (!$assignment || $assignment->jenis_tugas !== 'quiz') {
                        $fail('Hanya tugas dengan jenis "quiz" yang dapat digunakan.');
                    }
                },
            ],
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_soal' => 'required|in:pilihan_ganda,esay,campuran',
            'waktu_pengerjaan' => 'required|date_format:Y-m-d\TH:i',
            'durasi' => 'required|integer|min:1',
            'attempt_limit' => 'required|integer|min:1',
            'quiz_password' => 'required|string',
        ]);

        Quiz::create([
            'id_assignment' => $request->id_assignment,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_soal' => $request->jenis_soal,
            'waktu_pengerjaan' => date('Y-m-d H:i:s', strtotime($request->waktu_pengerjaan)),
            'durasi' => $request->durasi,
            'attempt_limit' => $request->attempt_limit,
            'quiz_password' => bcrypt($request->quiz_password),
        ]);

        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil dibuat.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $assignments = Assignment::all();
        return view('quizzes.edit', compact('quiz', 'assignments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'id_assignment' => [
                'required',
                'exists:assignments,id',
                function ($attribute, $value, $fail) {
                    $assignment = Assignment::find($value);
                    if (!$assignment || $assignment->jenis_tugas !== 'quiz') {
                        $fail('Hanya tugas dengan jenis "quiz" yang dapat digunakan.');
                    }
                },
            ],
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_soal' => 'required|in:pilihan_ganda,esay,campuran',
            'waktu_pengerjaan' => 'required|date_format:Y-m-d\TH:i',
            'durasi' => 'required|integer|min:1',
            'attempt_limit' => 'required|integer|min:1', // Ditambahkan validasi
            'quiz_password' => 'nullable|string',
        ]);

        $data = [
            'id_assignment' => $request->id_assignment,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_soal' => $request->jenis_soal,
            'durasi' => $request->durasi,
            'attempt_limit' => $request->attempt_limit, // Pastikan attempt_limit ikut diperbarui
            'waktu_pengerjaan' => date('Y-m-d H:i:s', strtotime($request->waktu_pengerjaan)),
        ];

        if ($request->filled('quiz_password')) {
            $data['quiz_password'] = bcrypt($request->quiz_password);
        }

        $quiz->update($data);

        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil diperbarui.');
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil dihapus.');
    }
}
