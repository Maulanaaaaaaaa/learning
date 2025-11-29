<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h1>Hi, {{ Auth::user()->dosen->nama }} 👋</h1>
        {{-- <h1>Dashboard Dosen</h1> --}}

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Persetujuan</h5>
                        <p class="card-text">Persetujuan Matakuliah ditempuh</p>
                        <a href="/kelolamatakuliah" class="btn btn-primary">Lihat</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Materi Tugas dan Quiz</h5>
                        <p class="card-text">Tambah Materi Tugas dan Quiz.</p>
                        <a href="/buattugas" class="btn btn-primary">Tambah</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
