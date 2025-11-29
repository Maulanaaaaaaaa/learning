<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h2>Kelola Soal Quiz: {{ $quiz->judul }}</h2>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Soal</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pertanyaan</th>
                    <th>Jenis</th>
                    <th>Opsi Jawaban</th>
                    <th>Jawaban Benar</th>
                    <th>Bobot Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $index => $question)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $question->pertanyaan }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $question->jenis_pertanyaan)) }}</td>
                        <td>
                            @if ($question->jenis_pertanyaan == 'pilihan_ganda')
                                <ul>
                                    @foreach ($question->opsi_jawaban as $key => $value)
                                        <li>{{ strtoupper($key) }}. {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td>{{ $question->jawaban_benar ?? '-' }}</td>
                        <td>{{ $question->bobot_nilai }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $question->id }}">Edit</button>
                            <form action="{{ route('quiz_questions.destroy', [$quiz->id, $question->id]) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus soal ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Soal -->
                    <div class="modal fade" id="modalEdit{{ $question->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Soal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('quiz_questions.update', [$quiz->id, $question->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label>Pertanyaan</label>
                                            <textarea name="pertanyaan" class="form-control" required>{{ $question->pertanyaan }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label>Jenis Soal</label>
                                            <select name="jenis_pertanyaan" class="form-control jenis-soal"
                                                data-id="{{ $question->id }}" required>
                                                @if ($quiz->jenis_soal == 'pilihan_ganda' || $quiz->jenis_soal == 'campuran')
                                                    <option value="pilihan_ganda"
                                                        {{ $question->jenis_pertanyaan == 'pilihan_ganda' ? 'selected' : '' }}>
                                                        Pilihan Ganda</option>
                                                @endif
                                                @if ($quiz->jenis_soal == 'esay' || $quiz->jenis_soal == 'campuran')
                                                    <option value="esay"
                                                        {{ $question->jenis_pertanyaan == 'esay' ? 'selected' : '' }}>
                                                        Esay</option>
                                                @endif
                                            </select>

                                        </div>
                                        <div id="opsiJawabanWrapper{{ $question->id }}"
                                            class="mb-3 {{ $question->jenis_pertanyaan == 'esay' ? 'd-none' : '' }}">
                                            <label>Opsi Jawaban</label>
                                            <div>
                                                <input type="text" name="opsi_jawaban[a]" class="form-control mb-2"
                                                    placeholder="Jawaban A"
                                                    value="{{ $question->opsi_jawaban['a'] ?? '' }}">
                                                <input type="text" name="opsi_jawaban[b]" class="form-control mb-2"
                                                    placeholder="Jawaban B"
                                                    value="{{ $question->opsi_jawaban['b'] ?? '' }}">
                                                <input type="text" name="opsi_jawaban[c]" class="form-control mb-2"
                                                    placeholder="Jawaban C"
                                                    value="{{ $question->opsi_jawaban['c'] ?? '' }}">
                                                <input type="text" name="opsi_jawaban[d]" class="form-control mb-2"
                                                    placeholder="Jawaban D"
                                                    value="{{ $question->opsi_jawaban['d'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="mb-3" id="jawabanBenarWrapper{{ $question->id }}"
                                            @if ($question->jenis_pertanyaan == 'esay') style="display: none;" @endif>
                                            <label>Jawaban Benar</label>
                                            <input type="text" name="jawaban_benar" class="form-control"
                                                value="{{ $question->jawaban_benar }}">
                                        </div>
                                        <div class="mb-3">
                                            <label>Bobot Nilai</label>
                                            <input type="number" name="bobot_nilai" class="form-control" required
                                                value="{{ $question->bobot_nilai }}">
                                        </div>
                                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('quiz_questions.store', $quiz->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Soal</label>
                            <select name="jenis_pertanyaan" class="form-control" id="jenisSoalTambah" required>
                                @if ($quiz->jenis_soal == 'pilihan_ganda' || $quiz->jenis_soal == 'campuran')
                                    <option value="pilihan_ganda">Pilihan Ganda</option>
                                @endif
                                @if ($quiz->jenis_soal == 'esay' || $quiz->jenis_soal == 'campuran')
                                    <option value="esay">Esay</option>
                                @endif
                            </select>

                        </div>
                        <div id="opsiJawabanWrapperTambah" class="mb-3">
                            <label>Opsi Jawaban</label>
                            <div>
                                <input type="text" name="opsi_jawaban[a]" class="form-control mb-2"
                                    placeholder="Jawaban A">
                                <input type="text" name="opsi_jawaban[b]" class="form-control mb-2"
                                    placeholder="Jawaban B">
                                <input type="text" name="opsi_jawaban[c]" class="form-control mb-2"
                                    placeholder="Jawaban C">
                                <input type="text" name="opsi_jawaban[d]" class="form-control mb-2"
                                    placeholder="Jawaban D">
                            </div>
                        </div>
                        <div class="mb-3" id="jawabanBenarWrapperTambah">
                            <label>Jawaban Benar</label>
                            <input type="text" name="jawaban_benar" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Bobot Nilai</label>
                            <input type="number" name="bobot_nilai" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Tambah Soal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function toggleFields(selectElement) {
                let id = selectElement.dataset.id ? selectElement.dataset.id : 'Tambah';
                let opsiWrapper = document.getElementById('opsiJawabanWrapper' + id);
                let jawabanBenarWrapper = document.getElementById('jawabanBenarWrapper' + id);
    
                if (selectElement.value === 'pilihan_ganda') {
                    opsiWrapper.style.display = 'block';
                    jawabanBenarWrapper.style.display = 'block';
                } else {
                    opsiWrapper.style.display = 'none';
                    jawabanBenarWrapper.style.display = 'none';
                }
            }
    
            // Event listener untuk dropdown tambah soal
            let jenisSoalTambah = document.getElementById('jenisSoalTambah');
            if (jenisSoalTambah) {
                jenisSoalTambah.addEventListener('change', function () {
                    toggleFields(this);
                });
                toggleFields(jenisSoalTambah); // Panggil saat pertama kali halaman dimuat
            }
    
            // Event listener untuk dropdown edit soal
            document.querySelectorAll('.jenis-soal').forEach(select => {
                select.addEventListener('change', function () {
                    toggleFields(this);
                });
                toggleFields(select); // Panggil saat pertama kali halaman dimuat
            });
        });
    </script>
    

</body>

</html>
