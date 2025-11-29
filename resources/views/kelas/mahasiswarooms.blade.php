<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mahasiswa Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <x-header></x-header>
    <h2>Daftar Mahasiswa dalam Kelas</h2>

    <!-- Button untuk membuka modal -->
    <button type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
        Tambah Mahasiswa ke Kelas
    </button>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
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

    <!-- Tabel Data Mahasiswa dalam Kelas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mahasiswa</th>
                <th>Email</th>
                <th>Kode Kelas</th>
                <th>Nama Ruangan</th>
                <th>Aksi</th> <!-- Kolom baru untuk aksi -->
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswaRooms as $key => $mr)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $mr->mahasiswa->nama }}</td>
                    <td>{{ $mr->mahasiswa->email }}</td>
                    <td>{{ $mr->room->id }}</td>
                    <td>{{ $mr->room->nama_ruangan }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMahasiswaModal{{ $mr->id }}">
                            Edit
                        </button>
    
                        <!-- Tombol Hapus -->
                        <form action="{{ route('mahasiswarooms.destroy', $mr->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
    
                <!-- Modal Edit untuk setiap baris -->
                <div class="modal fade" id="editMahasiswaModal{{ $mr->id }}" tabindex="-1" aria-labelledby="editMahasiswaModalLabel{{ $mr->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMahasiswaModalLabel{{ $mr->id }}">Edit Mahasiswa dalam Kelas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('mahasiswarooms.update', $mr->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="id_mahasiswa" class="form-label">Pilih Mahasiswa</label>
                                        <select name="id_mahasiswa" id="id_mahasiswa" class="form-control" required>
                                            <option value="">-- Pilih Mahasiswa --</option>
                                            @foreach($mahasiswa as $mhs)
                                                <option value="{{ $mhs->id }}" {{ $mr->id_mahasiswa == $mhs->id ? 'selected' : '' }}>{{ $mhs->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
    
                                    <div class="mb-3">
                                        <label for="id_room" class="form-label">Pilih Kelas</label>
                                        <select name="id_room" id="id_room" class="form-control" required>
                                            <option value="">-- Pilih Ruangan --</option>
                                            @foreach($rooms as $room)
                                                <option value="{{ $room->id }}" {{ $mr->id_room == $room->id ? 'selected' : '' }}>{{ $room->id }} ({{ $room->nama_ruangan }})</option>
                                            @endforeach
                                        </select>
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
    <!-- Modal untuk menambahkan mahasiswa ke kelas -->
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-labelledby="addMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMahasiswaModalLabel">Tambah Mahasiswa ke Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('mahasiswarooms.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="id_mahasiswa" class="form-label">Pilih Mahasiswa</label>
                            <select name="id_mahasiswa" id="id_mahasiswa" class="form-control" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="id_room" class="form-label">Pilih Ruangan</label>
                            <select name="id_room" id="id_room" class="form-control" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->id }} ({{ $room->nama_ruangan }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
