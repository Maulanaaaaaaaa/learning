<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <x-header></x-header>
    <div class="container mt-5">
        <h2>Manajemen Kelas</h2>

        <!-- Alert untuk success dan error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">Tambah Kelas</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Admin</th>
                    <th>Prodi</th>
                    <th>Nama Kelas</th>
                    <th>Semester</th>
                    <th>Kode Kelas</th>
                    <th>Jenis Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelass as $index => $kelas)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kelas->admin->nama }}</td>
                    <td>{{ $kelas->prodi->kode_prodi }} - {{ $kelas->prodi->nama_prodi }}</td>
                    <td>{{ ucfirst($kelas->nama_kelas) }}</td>
                    <td>{{ $kelas->semester }}</td>
                    <td>{{ $kelas->kode_kelas }}</td>
                    <td>{{ ucfirst($kelas->jenis_kelas) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKelasModal{{ $kelas->id }}">Edit</button>
                        <form action="{{ route('kelass.destroy', $kelas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Kelas -->
                <div class="modal fade" id="editKelasModal{{ $kelas->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kelas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('kelass.update', $kelas->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="id_admin" class="form-label">Admin</label>
                                        <select name="id_admin" class="form-control" required>
                                            <option value="">Pilih Admin</option>
                                            @foreach (App\Models\Admin::all() as $admin)
                                                <option value="{{ $admin->id }}" {{ $kelas->id_admin == $admin->id ? 'selected' : '' }}>
                                                    {{ $admin->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_prodi" class="form-label">Program Studi</label>
                                        <select name="id_prodi" class="form-control" required>
                                            <option value="">Pilih Prodi</option>
                                            @foreach (App\Models\Prodi::all() as $prodi)
                                                <option value="{{ $prodi->id }}" {{ $kelas->id_prodi == $prodi->id ? 'selected' : '' }}>
                                                   {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label>Nama Kelas</label>
                                        <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Semester</label>
                                        <input type="number" name="semester" class="form-control" value="{{ old('semester', $kelas->semester) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Kelas</label>
                                        <input type="text" name="kode_kelas" class="form-control" value="{{ old('kode_kelas', $kelas->kode_kelas) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jenis Kelas</label>
                                        <select name="jenis_kelas" class="form-control">
                                            <option value="pagi" {{ $kelas->jenis_kelas == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                            <option value="malam" {{ $kelas->jenis_kelas == 'malam' ? 'selected' : '' }}>Malam</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="tambahKelasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kelass.store') }}" method="POST">
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
                            <label for="id_prodi" class="form-label">Program Studi</label>
                            <select name="id_prodi" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach (App\Models\Prodi::all() as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>                                       
                        <div class="mb-3">
                            <label>Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Semester</label>
                            <input type="number" name="semester" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Kode Kelas</label>
                            <input type="text" name="kode_kelas" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Kelas</label>
                            <select name="jenis_kelas" class="form-control">
                                <option value="pagi">Pagi</option>
                                <option value="malam">Malam</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
