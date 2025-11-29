<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Attempts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h2 class="mb-4">Kelola Attempts</h2>

        <!-- Notifikasi -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tombol Tambah Attempt -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Attempt</button>

        <!-- Tabel Attempts -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
        <th>Mahasiswa</th>
        <th>Attempt Ke</th>
        <th>Total Skor</th>
        <th>Waktu Mulai</th>
        <th>Waktu Selesai</th>
        <th>Hitungan Mundur</th> <!-- Tambahkan ini -->
        <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attempts as $index => $attempt)
                    <tr>
                        <td>{{ $index + 1 }}</td>
            <td>{{ $attempt->mahasiswa->nama }}</td>
            <td>{{ $attempt->attempt_number }}</td>
            <td>{{ $attempt->total_score }}</td>
            <td>{{ $attempt->started_at }}</td>
            <td>{{ $attempt->ended_at ?? 'Belum Selesai' }}</td>
            <td>
                @if ($attempt->ended_at && now()->lessThan($attempt->ended_at))
                    <span class="timer" data-end="{{ \Carbon\Carbon::parse($attempt->ended_at)->format('Y-m-d H:i:s') }}"></span>
                @else
                    Waktu Habis
                @endif
            </td>
            
            <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $attempt->id }}">Edit</button>
                <form action="{{ route('attempts.destroy', ['id_quiz' => $id_quiz, 'id' => $attempt->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus attempt ini?')">Hapus</button>
                </form>
            </td>
                    </tr>

                    <!-- Modal Edit Attempt -->
                    <div class="modal fade" id="modalEdit{{ $attempt->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Attempt</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('attempts.update', ['id_quiz' => $id_quiz, 'id' => $attempt->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label>Total Skor</label>
                                            <input type="number" name="total_score" class="form-control" value="{{ $attempt->total_score }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Waktu Selesai</label>
                                            <input type="datetime-local" name="ended_at" class="form-control" value="{{ $attempt->ended_at ? date('Y-m-d\TH:i', strtotime($attempt->ended_at)) : '' }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Attempt -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Attempt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attempts.store', $id_quiz) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                            <select name="id_mahasiswa" class="form-control" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach (App\Models\Mahasiswa::all() as $mahasiswa)
                                    <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Attempt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function startTimers() {
                document.querySelectorAll(".timer").forEach(function (timer) {
                    let endTime = new Date(timer.getAttribute("data-end")).getTime();
    
                    function updateTimer() {
                        let now = new Date().getTime();
                        let remaining = endTime - now;
    
                        if (remaining <= 0) {
                            timer.innerHTML = "Waktu Habis";
                        } else {
                            let hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            let minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                            let seconds = Math.floor((remaining % (1000 * 60)) / 1000);
    
                            timer.innerHTML = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                            setTimeout(updateTimer, 1000);
                        }
                    }
    
                    updateTimer();
                });
            }
    
            startTimers();
        });
    </script>
    
    
    
</body>
</html>
