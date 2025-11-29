<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\MahasiswaRoomController;
use App\Http\Controllers\KelassController;


// 📌 Halaman untuk user yang belum login (guest)
Route::middleware('guest')->group(function () {
    Route::view('/', 'home');
    Route::view('/forgot', 'reset');

    // 🔹 Login & Register
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterMahasiswaForm'])->name('register');
    Route::post('/register', [AuthController::class, 'registerMahasiswa']);
    Route::get('/registerdosen', [AuthController::class, 'showRegisterDosenForm'])->name('registerdosen');
    Route::post('/registerdosen', [AuthController::class, 'registerDosen']);
});

// 📌 Logout hanya untuk pengguna yang sudah login
Route::middleware('auth')->get('/logout', [AuthController::class, 'logout'])->name('logout');

// 📌 Mahasiswa yang sudah login
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/index', [MatakuliahController::class, 'Dashboard'])->name('dashboard');
    Route::get('/homes', [MatakuliahController::class, 'homes']);
    Route::post('/enroll/{id}', [MatakuliahController::class, 'enroll'])->name('enroll.matakuliah');
    Route::get('/coursematakuliah', [MatakuliahController::class, 'courseMatakuliah'])->name('coursematakuliah');
    Route::get('/api/users-status', [UserController::class, 'getUsersStatus']);

    Route::get('/quizzesmahasiswa{id_quiz}', [QuizController::class, 'indexMahasiswa'])->name('quizzes.mahasiswa');
    Route::post('/quizzesmahasiswa/{quiz}/verify', [QuizController::class, 'verify'])->name('quizzes.verify');

    Route::get('/quizmaha/{id_quiz}', [QuizQuestionController::class, 'indexFern'])->name('quizmaha.index');

    Route::post('/quizmaha/{id_quiz}/submit', [QuizQuestionController::class, 'submit'])->name('quizmaha.submit');

    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::put('/submissions/{id}', [SubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('/submissions/{id}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
});

// 📌 Dosen yang sudah login
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::view('/dosen', 'db.dosen'); // Dashboard Dosen
    Route::view('/matakuliah', 'matakuliah.matakuliah'); // Halaman Mata Kuliah

    // 🔹 Manajemen Mata Kuliah oleh Dosen
    Route::get('/kelolamatakuliah', [MatakuliahController::class, 'indexDosen'])->name('kelola.matakuliah.dosen');
    Route::put('/kelola/matakuliah/{id}/persetujuan', [MatakuliahController::class, 'updatePersetujuan'])->name('matakuliah.persetujuan');
    Route::put('/enrolment/{id}/approve', [MatakuliahController::class, 'approve'])->name('enrolment.approve');

    Route::prefix('quizzes')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('quizzes.index');
        Route::post('/', [QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    });

    Route::prefix('quiz/{id_quiz}/questions')->group(function () {
        Route::get('/', [QuizQuestionController::class, 'index'])->name('quiz_questions.index');
        Route::post('/', [QuizQuestionController::class, 'store'])->name('quiz_questions.store');
        Route::get('/{id_question}/edit', [QuizQuestionController::class, 'edit'])->name('quiz_questions.edit');
        Route::put('/{id_question}/update', [QuizQuestionController::class, 'update'])->name('quiz_questions.update');
        Route::delete('/{id_question}/destroy', [QuizQuestionController::class, 'destroy'])->name('quiz_questions.destroy');
    });

    Route::prefix('quizzes/{id_quiz}/attempts')->group(function () {
        Route::get('/', [QuizAttemptController::class, 'index'])->name('attempts.index');
        Route::post('/', [QuizAttemptController::class, 'store'])->name('attempts.store');
        Route::get('/{id}/edit', [QuizAttemptController::class, 'edit'])->name('attempts.edit');
        Route::put('/{id}', [QuizAttemptController::class, 'update'])->name('attempts.update');
        Route::delete('/{id}', [QuizAttemptController::class, 'destroy'])->name('attempts.destroy');
    });

    Route::prefix('quiz_attempts/{id_attempt}/answers')->group(function () {
        Route::get('/', [QuizAnswerController::class, 'index'])->name('quiz_answers.index');
        Route::post('/{id_question}', [QuizAnswerController::class, 'store'])->name('quiz_answers.store');
    });

    Route::get('/nilaiesay', [QuizAnswerController::class, 'index'])->name('nilaiesay.index');
    Route::post('/nilaiesay/{id}', [QuizAnswerController::class, 'updateNilaiEsai'])->name('nilaiesay.update');

    Route::get('/submission', [SubmissionController::class, 'submissionIndex'])->name('submissions.index');
    Route::put('/submissions/{id}/nilai', [SubmissionController::class, 'updateNilai'])->name('submissions.updateNilai');
    Route::delete('/submissionss/{id}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');

    Route::prefix('buattugas')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('assignments.index'); // Menampilkan semua tugas
        // Route::get('/tambah', [AssignmentController::class, 'create'])->name('assignments.create'); // Form tambah tugas
        Route::post('/simpan', [AssignmentController::class, 'store'])->name('assignments.store'); // Simpan tugas
        // Route::get('/{id}', [AssignmentController::class, 'show'])->name('assignments.show'); // Detail tugas
        Route::get('/{id}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit'); // Form edit tugas
        Route::put('/{id}', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('/{id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy'); // Hapus tugas
    });
});

// 📌 Admin yang sudah login
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/admin', 'db.admin'); // Dashboard Admin

    // 🔹 Manajemen Pengguna (Mahasiswa & Dosen)
    Route::prefix('kelola')->name('kelola.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('{id}/update-status', [UserController::class, 'updateStatus'])->name('update-status');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });

    // 🔹 Manajemen Dosen
    Route::prefix('keloladosen')->name('keloladosen.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('{id}/update-status', [UserController::class, 'updateStatus'])->name('update-status');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });

    // 🔹 Manajemen Mata Kuliah
    Route::prefix('kelolamatkul')->name('matakuliah.')->group(function () {
        Route::get('/', [MatakuliahController::class, 'index'])->name('index');
        Route::post('/store', [MatakuliahController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MatakuliahController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MatakuliahController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MatakuliahController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
        Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');  // Diperbaiki
        Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');   // Diperbaiki
        Route::delete('/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    });
    Route::prefix('rooms/schedules')->group(function () {
        Route::get('/{id}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    Route::prefix('kelolaprodi')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('prodi.index');
        Route::get('/create', [ProdiController::class, 'create'])->name('prodi.create');
        Route::post('/store', [ProdiController::class, 'store'])->name('prodi.store');
        Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store');
        Route::put('/kelolaprodi/update/{id}', [ProdiController::class, 'update'])->name('prodi.update');
        Route::delete('/kelolaprodi/delete/{id}', [ProdiController::class, 'destroy'])->name('prodi.destroy');
    });

    Route::prefix('mahasiswarooms')->name('mahasiswarooms.')->group(function () {
        Route::get('/', [MahasiswaRoomController::class, 'index'])->name('index');
        Route::post('/', [MahasiswaRoomController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MahasiswaRoomController::class, 'edit'])->name('edit');   
        Route::put('/{id}', [MahasiswaRoomController::class, 'update'])->name('update');
        Route::delete('/{id}', [MahasiswaRoomController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('buatkelas')->group(function () {
        Route::get('/', [KelassController::class, 'index'])->name('kelass.index');
        Route::post('/', [KelassController::class, 'store'])->name('kelass.store');
        Route::get('/{kelas}/edit', [KelassController::class, 'edit'])->name('kelass.edit'); // Tambah route edit
        Route::put('/{kelas}', [KelassController::class, 'update'])->name('kelass.update');
        Route::delete('/{kelas}', [KelassController::class, 'destroy'])->name('kelass.destroy');
    });
    

    
});
