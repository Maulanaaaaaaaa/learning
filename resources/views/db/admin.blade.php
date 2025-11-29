<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h1>hi, {{ Auth::user()->admin->nama }} 👋</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kelola Mahasiswa</h5>
                        <p class="card-text">Tambah, hapus, atau kelola data mahasiswa.</p>
                        <a href="/kelola" class="btn btn-primary">Kelola</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kelola Dosen</h5>
                        <p class="card-text">Tambah, hapus, atau kelola data dosen.</p>
                        <a href="/keloladosen" class="btn btn-primary">Kelola</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kelola Matakuliah</h5>
                        <p class="card-text">Kelola data Matakuliah yang tersedia.</p>
                        <a href="/kelolamatkul" class="btn btn-primary">Kelola</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
