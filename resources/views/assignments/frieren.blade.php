<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <x-header></x-header>
    
    <div class="container mt-4">
        @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->has('quiz_password'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle"></i> {{ $errors->first('quiz_password') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
        @forelse($quizzes as $quiz)
            <h2 class="text-primary fw-bold"><i class="fas fa-graduation-cap"></i> Quiz Mahasiswa: {{ $quiz->judul }}
            </h2>
            <p class="text-muted">Berikut adalah daftar quiz yang tersedia untuk mahasiswa.</p>

            @php
                $borderColor = match ($quiz->jenis_soal) {
                    'pilihan_ganda' => 'border-primary',
                    'esay' => 'border-success',
                    'campuran' => 'border-warning',
                    default => 'border-secondary',
                };
            @endphp

            <div class="card shadow-sm p-4 mb-4 {{ $borderColor }}">
                

                <h3 class="mt-2 fw-bold text-dark"><i class="fas fa-file-alt"></i> {{ $quiz->judul }}</h3>
                <p class="text-secondary">{{ $quiz->deskripsi }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-warning text-dark"><i class="fas fa-question-circle"></i> Jenis Soal:
                        {{ ucfirst(str_replace('_', ' ', $quiz->jenis_soal)) }}</span>
                    <span class="badge bg-primary"><i class="fas fa-clock"></i> Durasi: {{ $quiz->durasi }} menit</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="badge @if ($quiz->waktu_pengerjaan && \Carbon\Carbon::parse($quiz->waktu_pengerjaan)->isPast()) bg-danger @else bg-success @endif">
                        <i class="fas fa-calendar-alt"></i> Waktu:
                        {{ \Carbon\Carbon::parse($quiz->waktu_pengerjaan)->translatedFormat('d F Y H:i') }}
                    </span>
                </div>

                @php
                    $ongoingAttempt = \App\Models\QuizAttempt::where('id_quiz', $quiz->id)
                        ->where('id_mahasiswa', Auth::user()->mahasiswa->id ?? null)
                        ->where('ended_at', '>', now())
                        ->first();
                @endphp

                <div class="mt-4">
                    @if ($quiz->waktu_pengerjaan && \Carbon\Carbon::parse($quiz->waktu_pengerjaan)->isPast())
                        <button class="btn btn-danger btn-sm" disabled>
                            <i class="fas fa-times"></i> Waktu pengerjaan sudah lewat
                        </button>
                    @elseif($ongoingAttempt)
                        <a href="{{ route('quizmaha.index', ['id_quiz' => $quiz->id, 'timer' => \Carbon\Carbon::parse($ongoingAttempt->ended_at)->timestamp * 1000]) }}"
                            class="btn btn-warning btn-sm">
                            <i class="fas fa-play"></i> Lanjutkan Quiz
                        </a>
                    @else
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#passwordModal{{ $quiz->id }}">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                    @endif

                    <!-- Button untuk Riwayat Quiz -->
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#historyModal{{ $quiz->id }}">
                        <i class="fas fa-history"></i> Riwayat Quiz
                    </button>
                </div>
            </div>

<!-- Modal Riwayat Quiz -->
<div class="modal fade" id="historyModal{{ $quiz->id }}" tabindex="-1"
    aria-labelledby="historyModalLabel{{ $quiz->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel{{ $quiz->id }}">
                    <i class="fas fa-history"></i> Riwayat Quiz: {{ $quiz->judul }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                    $attempts = \App\Models\QuizAttempt::with(['quiz', 'mahasiswa', 'answers.question'])
                        ->where('id_quiz', $quiz->id)
                        ->where('id_mahasiswa', Auth::user()->mahasiswa->id ?? null)
                        ->get();
                @endphp

                @if($attempts->isEmpty())
                    <p class="text-muted">Belum ada riwayat quiz.</p>
                @else
                    <!-- Tabel Riwayat Percobaan -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><i class="fas fa-list-ol"></i> Percobaan Ke-</th>
                                <th><i class="fas fa-star"></i> Skor</th>
                                <th><i class="fas fa-calendar-alt"></i> Waktu Mulai</th>
                                <th><i class="fas fa-calendar-check"></i> Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $attempt)
                                <tr>
                                    <td>{{ $attempt->attempt_number }}</td>
                                    <td>{{ $attempt->total_score }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attempt->started_at)->translatedFormat('d F Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attempt->ended_at)->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Tabel Detail Jawaban -->
                    <h5 class="mt-4"><i class="fas fa-question-circle"></i> Detail Jawaban</h5>
                    @foreach($attempts as $attempt)
                        <div class="mb-4">
                            <h6>Percobaan Ke-{{ $attempt->attempt_number }}</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-question"></i> Pertanyaan</th>
                                        <th><i class="fas fa-check-circle"></i> Jawaban Diberikan</th>
                                        <th><i class="fas fa-star"></i> Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attempt->answers as $answer)
                                        <tr>
                                            <td>{{ $answer->question->pertanyaan }}</td>
                                            <td>
                                                @if($answer->question->jenis_pertanyaan == 'pilihan_ganda')
                                                    {{ $answer->opsi }}
                                                @else
                                                    {{ $answer->jawaban_teks }}
                                                @endif
                                            </td>
                                            <td>{{ $answer->nilai ?? 'Belum dinilai' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
            <!-- Modal Password -->
            <div class="modal fade" id="passwordModal{{ $quiz->id }}" tabindex="-1"
                aria-labelledby="passwordModalLabel{{ $quiz->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="passwordModalLabel{{ $quiz->id }}"><i
                                    class="fas fa-lock"></i> Masukkan Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('quizzes.verify', $quiz->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="quiz_password_{{ $quiz->id }}" class="form-label"><i
                                            class="fas fa-key"></i> Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="quiz_password"
                                            id="quiz_password_{{ $quiz->id }}" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword({{ $quiz->id }})">
                                            <i id="eyeIcon_{{ $quiz->id }}" class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="card p-4 border-secondary">
                <h3 class="mt-3 text-secondary">Belum ada quiz tersedia.</h3>
            </div>
        @endforelse
    </div>

    <script>
        function togglePassword(quizId) {
            let passwordInput = document.getElementById(`quiz_password_${quizId}`);
            let eyeIcon = document.getElementById(`eyeIcon_${quizId}`);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
