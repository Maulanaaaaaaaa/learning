<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jawaban Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/megumin.css">
</head>

<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h2>Daftar Submission</h2>

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

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Jenis Tugas</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Waktu Pengumpulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissions as $index => $submission)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                            <td>{{ $submission->mahasiswa->nama }}</td>
                            <td>{{ $submission->assignment->jenis_tugas }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $submission->file) }}" target="_blank">
                                    {{ $submission->original_name }}
                                </a>
                            </td>
                            <td>{{ $submission->status }}</td>
                            <td>
                                <form action="{{ route('submissions.updateNilai', $submission->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="nilai" value="{{ $submission->nilai ?? '' }}" class="form-control form-control-sm" min="0" max="100">
                                    <button type="submit" class="btn btn-primary btn-sm mt-1">Update</button>
                                </form>
                            </td>
                            
                            <td>{{ $submission->submitted_at }}</td>
                            <td>
                                <form action="{{ route('submissions.destroy', $submission->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
