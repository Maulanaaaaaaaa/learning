<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <x-header></x-header>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Quiz: {{ $quiz->judul }}</h2>
            <div id="timer" class="badge bg-danger fs-5 p-2"></div>
        </div>

        <form action="{{ route('quizmaha.submit', $quiz->id) }}" method="POST">
            @csrf
            <div class="card shadow-sm p-4">
                <div id="question-container">
                    @foreach ($questions as $index => $question)
                        <div class="question-item" style="display: none;">
                            <h5 class="text-secondary">Pertanyaan {{ $index + 1 }}</h5>
                            <p class="fs-5">{{ $question->pertanyaan }}</p>

                            @if ($question->jenis_pertanyaan == 'pilihan_ganda')
                                @foreach ($question->opsi_jawaban as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="jawaban[{{ $question->id }}]" value="{{ $key }}" required>
                                        <label class="form-check-label">
                                            {{ strtoupper($key) }}. {{ $value }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <textarea class="form-control mt-2" name="jawaban[{{ $question->id }}]" rows="3" required></textarea>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Progress Indicator -->
                <div class="text-center mt-3">
                    <span id="progress-indicator" class="fs-6 text-muted"></span>
                </div>

                <!-- Tombol Navigasi -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary" id="prevButton" style="display: none;">
                        <i class="bi bi-arrow-left-circle"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary" id="nextButton">
                        Selanjutnya <i class="bi bi-arrow-right-circle"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitButton" style="display: none;">
                        <i class="bi bi-send-check"></i> Kirim Jawaban
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- SCRIPT NAVIGASI -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentQuestionIndex = 0;
            let questions = document.querySelectorAll(".question-item");
            let totalQuestions = questions.length;
            let prevButton = document.getElementById("prevButton");
            let nextButton = document.getElementById("nextButton");
            let submitButton = document.getElementById("submitButton");
            let progressIndicator = document.getElementById("progress-indicator");

            function showQuestion(index) {
                questions.forEach((question, i) => {
                    question.style.display = i === index ? "block" : "none";
                });

                progressIndicator.innerText = `Soal ${index + 1} dari ${totalQuestions}`;

                prevButton.style.display = index === 0 ? "none" : "inline-block";
                nextButton.style.display = index === totalQuestions - 1 ? "none" : "inline-block";
                submitButton.style.display = index === totalQuestions - 1 ? "inline-block" : "none";
            }

            prevButton.addEventListener("click", function () {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                }
            });

            nextButton.addEventListener("click", function () {
                if (currentQuestionIndex < totalQuestions - 1) {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                }
            });

            showQuestion(currentQuestionIndex);
        });
    </script>

    <!-- SCRIPT TIMER -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let endTime = {{ request()->get('timer', 0) }}; 
            let timerElement = document.getElementById("timer");
            let quizForm = document.querySelector("form");

            function updateTimer() {
                let now = new Date().getTime();
                let distance = endTime - now;

                if (distance <= 0) {
                    timerElement.innerHTML = "Waktu habis!";
                    quizForm.submit(); 
                    return;
                }

                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerElement.innerHTML = `⏳ ${hours}j ${minutes}m ${seconds}d`;

                setTimeout(updateTimer, 1000);
            }

            if (endTime > 0) {
                updateTimer();
            }
        });
    </script>

</body>

</html>
