<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/megumin.css">
</head>

<body>
    <x-header></x-header>
    <div class="container my-4">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Tombol Tambah Mata Kuliah -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahMatakuliahModal">Tambah Mata
            Kuliah</button>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Tabel Data Mata Kuliah -->
        <div class="card">
            <div class="card-body">
                <h3>Kelola Mata Kuliah</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Prodi</th>
                                <th>Kode Matakuliah</th>
                                <th>Nama Matakuliah</th>
                                <th>Nama Admin</th>
                                <th>Nama Dosen</th>
                                <th>SKS</th>
                                <th>Deskripsi</th>
                                <th>Persetujuan Dosen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matakuliahs as $key => $matakuliah)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                   
                                    <td>{{ $matakuliah->prodi->kode_prodi }} - {{ $matakuliah->prodi->nama_prodi }}</td>
                                    <td>{{ $matakuliah->kode_mk }}</td>
                                    <td>{{ $matakuliah->nama_matakuliah }}</td>
                                    <td>{{ $matakuliah->admin->nama ?? 'Tidak ada' }}</td>
                                    <td>{{ $matakuliah->dosen->nama ?? 'Tidak ada' }}</td>
                                    <td>{{ $matakuliah->sks }}</td>
                                    <td>{{ $matakuliah->deskripsi }}</td>
                                    <td>
                                        @if ($matakuliah->status_persetujuan === 'menunggu persetujuan')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                        @elseif ($matakuliah->status_persetujuan === 'setuju')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Disetujui</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm edit-matakuliah"
                                            data-id="{{ $matakuliah->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editMatakuliahModal">
                                            Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('matakuliah.destroy', $matakuliah->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data mata kuliah</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Mata Kuliah -->
    <div class="modal fade" id="tambahMatakuliahModal" tabindex="-1" aria-labelledby="tambahMatakuliahModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('matakuliah.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahMatakuliahModalLabel">Tambah Mata Kuliah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" name="kode_mk" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_matakuliah" class="form-label">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" name="nama_matakuliah" required>
                        </div>
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
                            <label for="id_dosen" class="form-label">Dosen</label>
                            <select name="id_dosen" class="form-control" required>
                                <option value="">Pilih Dosen</option>
                                @foreach (App\Models\Dosen::all() as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_prodi" class="form-label">Prodi</label>
                            <select name="id_prodi" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach (App\Models\Prodi::all() as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sks" class="form-label">SKS</label>
                            <input type="number" class="form-control" name="sks" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Mata Kuliah -->
    <div class="modal fade" id="editMatakuliahModal" tabindex="-1" aria-labelledby="editMatakuliahModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMatakuliahModalLabel">Edit Mata Kuliah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_kode_mk" class="form-label">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" id="edit_kode_mk" name="kode_mk" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_matakuliah" class="form-label">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" id="edit_nama_matakuliah"
                                name="nama_matakuliah" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_dosen" class="form-label">Dosen</label>
                            <select id="edit_id_dosen" name="id_dosen" class="form-control" required>
                                <option value="">Pilih Dosen</option>
                                @foreach (App\Models\Dosen::all() as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_prodi" class="form-label">Prodi</label>
                            <select id="edit_id_prodi" name="id_prodi" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach (App\Models\Prodi::all() as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_sks" class="form-label">SKS</label>
                            <input type="number" class="form-control" id="edit_sks" name="sks" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-matakuliah').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/kelolamatkul/edit/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('editForm').action = `/kelolamatkul/update/${data.id}`;
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_kode_mk').value = data.kode_mk;
                        document.getElementById('edit_nama_matakuliah').value = data.nama_matakuliah;
                        document.getElementById('edit_sks').value = data.sks;
                        document.getElementById('edit_deskripsi').value = data.deskripsi;
                        document.getElementById('edit_id_dosen').value = data.id_dosen;
                        document.getElementById('edit_id_prodi').value = data.id_prodi;
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Terjadi kesalahan saat mengambil data mata kuliah!');
                    });
            });
        });
    </script>
</body>

</html>
