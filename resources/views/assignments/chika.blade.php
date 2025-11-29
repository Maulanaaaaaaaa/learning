<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jawaban Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h2 class="mb-3">Daftar Jawaban Quiz</h2>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Attempt</th>
                        <th>Tipe Soal</th>
                        <th>Opsi</th>
                        <th>Jawaban Teks</th>
                        <th>Benar/Salah</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($answers as $index => $answer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $answer->attempt->mahasiswa->nama }}</td>
                            <td>
                                @if ($answer->question->jenis_pertanyaan == 'pilihan_ganda')
                                    Pilihan ganda
                                @elseif ($answer->question->jenis_pertanyaan == 'esay')
                                    Essay
                                @else
                                    {{ $answer->question->jenis_pertanyaan }}
                                @endif
                            </td>
                            <td>{{ $answer->opsi ?? '-' }}</td>
                            <td>{{ $answer->jawaban_teks ?? '-' }}</td>
                            <td>
                                @if ($answer->question->jenis_pertanyaan == 'esay')
                                    {{ $answer->nilai === null ? 'Belum Diproses' : 'Sudah Diproses' }}
                                @else
                                    {{ $answer->is_correct ? 'Benar' : 'Salah' }}
                                @endif
                            </td>
                            <td>
                                @if ($answer->question->jenis_pertanyaan == 'esay')
                                    <form action="{{ route('nilaiesay.update', $answer->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="nilai"
                                                value="{{ $answer->nilai ?? '' }}" min="0"
                                                max="{{ $answer->question->bobot_nilai }}">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                @else
                                    {{ $answer->nilai ?? '-' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
