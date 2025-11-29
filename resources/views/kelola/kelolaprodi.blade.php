<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Program Studi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <x-header></x-header>
    <div class="container mt-4">
        <h1 class="mb-4">Kelola Prodi</h1>
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <!-- Tombol Tambah Prodi -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Prodi</button>

        <!-- Tabel Program Studi -->
        <table class="table table-bordered">
            <thead class="table-hover">
                <tr>
                    <th>No</th>
                    <th>Kode Prodi</th>
                    <th>Nama Prodi</th>
                    <th>Admin</th> <!-- Tambahkan kolom ini -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prodis as $index => $prodi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $prodi->kode_prodi }}</td>
                    <td>{{ $prodi->nama_prodi }}</td>
                    <td>{{ $prodi->admin ? $prodi->admin->nama : 'Tidak Diketahui' }}</td> <!-- Menampilkan nama admin -->
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $prodi->id }}">Edit</button>
                        <form action="{{ route('prodi.destroy', $prodi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

    
    <!-- Modal Tambah Prodi -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Program Studi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('prodi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="id_admin" class="form-label">Admin</label>
                        <select name="id_admin" class="form-control" required>
                            <option value="">Pilih Admin</option>
                            @foreach (App\Models\Admin::all() as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode Prodi</label>
                        <input type="text" name="kode_prodi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Prodi</label>
                        <input type="text" name="nama_prodi" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


    @foreach ($prodis as $prodi)
    <!-- Modal Edit Prodi -->
    <div class="modal fade" id="modalEdit{{ $prodi->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Program Studi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('prodi.update', $prodi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Kode Prodi</label>
                            <input type="text" name="kode_prodi" class="form-control" value="{{ $prodi->kode_prodi }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Prodi</label>
                            <input type="text" name="nama_prodi" class="form-control" value="{{ $prodi->nama_prodi }}" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
