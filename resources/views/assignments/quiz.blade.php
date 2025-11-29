<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/megumin.css">
</head>

<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h2 class="mb-4">Kelola Quiz</h2>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <!-- Tombol Tambah Quiz -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Quiz</button>

        <!-- Tabel Quiz -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Assignment</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Jenis Soal</th>
                        <th>Waktu (Jam)</th>
                        <th>Durasi</th>
                        <th>Batas Attempts</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quizzes as $index => $quiz)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $quiz->assignment->judul }}</td>
                            <td>{{ $quiz->judul }}</td>
                            <td>{{ $quiz->deskripsi }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $quiz->jenis_soal)) }}</td>
                            <td>{{ $quiz->waktu_pengerjaan }}</td>
                            <td>{{ $quiz->durasi }} menit</td>
                            <td>{{ $quiz->attempt_limit }}</td>
                            <td>{{ $quiz->quiz_password }}</td>
                            <td>
                                <a href="{{ route('quiz_questions.index', $quiz->id) }}"
                                    class="btn btn-sm btn-info">Kelola Soal</a>
                                <a href="{{ route('attempts.index', $quiz->id) }}"
                                    class="btn btn-sm btn-secondary">Kelola Attempts</a>
                                    <a href="{{ route('nilaiesay.index') }}" class="btn btn-sm btn-success">Nilai Esai</a>

                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $quiz->id }}">Edit</button>
                                <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus quiz ini?')">Hapus</button>
                                </form>
                            </td>


                        </tr>

                        <div class="modal fade" id="modalEdit{{ $quiz->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Quiz</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="id_assignment" class="form-label">Assignment</label>
                                                <select name="id_assignment" class="form-control" required>
                                                    @foreach (App\Models\Assignment::where('jenis_tugas', 'quiz')
                                                        ->where(function ($query) use ($quiz) {
                                                            $query->where('id_dosen', auth()->user()->id)
                                                                  ->orWhere('id', $quiz->id_assignment);
                                                        })
                                                        ->get() as $assignment)
                                                        <option value="{{ $assignment->id }}" 
                                                            {{ $quiz->id_assignment == $assignment->id ? 'selected' : '' }}>
                                                            {{ $assignment->judul }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Judul</label>
                                                <input type="text" name="judul" class="form-control"
                                                    value="{{ $quiz->judul }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control">{{ $quiz->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Jenis Soal</label>
                                                <select name="jenis_soal" class="form-control" required>
                                                    <option value="pilihan_ganda"
                                                        {{ $quiz->jenis_soal == 'pilihan_ganda' ? 'selected' : '' }}>
                                                        Pilihan Ganda</option>
                                                    <option value="esay"
                                                        {{ $quiz->jenis_soal == 'esay' ? 'selected' : '' }}>Esay
                                                    </option>
                                                    <option value="campuran"
                                                        {{ $quiz->jenis_soal == 'campuran' ? 'selected' : '' }}>
                                                        Campuran</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Waktu Pengerjaan (menit)</label>
                                                <input type="datetime-local" name="waktu_pengerjaan"
                                                    class="form-control"
                                                    value="{{ old('waktu_pengerjaan', $quiz->waktu_pengerjaan ? date('Y-m-d\TH:i', strtotime($quiz->waktu_pengerjaan)) : '') }}"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Durasi (menit)</label>
                                                <input type="number" name="durasi" class="form-control" value="{{ $quiz->durasi }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Batas Attempts</label>
                                                <input type="number" name="attempt_limit" class="form-control" value="{{ $quiz->attempt_limit }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                                                <input type="password" name="quiz_password" class="form-control">
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
    </div>



    <!-- Modal Tambah Quiz -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('quizzes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_assignment" class="form-label">Assignment</label>
                            <select name="id_assignment" class="form-control" required>
                                <option value="">Pilih Judul</option>
                                @foreach (App\Models\Assignment::where('jenis_tugas', 'quiz')->get() as $assignment)
                                    <option value="{{ $assignment->id }}">{{ $assignment->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Judul</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Soal</label>
                            <select name="jenis_soal" class="form-control" required>
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="esay">Esay</option>
                                <option value="campuran">Campuran</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Waktu Pengerjaan (menit)</label>
                            <input type="datetime-local" name="waktu_pengerjaan" class="form-control"
                                value="{{ old('waktu_pengerjaan') }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Durasi (menit)</label>
                            <input type="number" name="durasi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Batas Attempts</label>
                            <input type="number" name="attempt_limit" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="text" name="quiz_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Quiz</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
