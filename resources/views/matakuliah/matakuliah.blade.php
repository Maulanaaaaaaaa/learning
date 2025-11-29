<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mata Kuliah (Dosen)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/megumin.css">
</head>
<body>
    <x-header></x-header>
    <div class="container my-4">
        <h1 class="mb-4">Persetujuan (Dosen)</h1>

        <!-- Tabel Data Mata Kuliah -->
        <div class="card mb-4">
            <div class="card-body">
                <h3>Persetujuan Mata Kuliah</h3>
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Diploma</th>
                            <th>Prodi</th>
                            <th>Kode Matakuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Nama Admin</th>
                            <th>SKS</th>
                            <th>Deskripsi</th>
                            <th>Status Persetujuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($matakuliahs as $key => $matakuliah)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $matakuliah->prodi->kode_prodi }}</td>
                                <td>{{ $matakuliah->prodi->nama_prodi }}</td>
                                <td>{{ $matakuliah->kode_mk }}</td>
                                <td>{{ $matakuliah->nama_matakuliah }}</td>
                                <td>{{ $matakuliah->admin->nama ?? 'Tidak ada' }}</td>
                                <td>{{ $matakuliah->sks }}</td>
                                <td>{{ $matakuliah->deskripsi }}</td>
                                <td>
                                    <span class="badge bg-{{ $matakuliah->status_persetujuan === 'setuju' ? 'success' : ($matakuliah->status_persetujuan === 'tidak setuju' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($matakuliah->status_persetujuan) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('matakuliah.persetujuan', $matakuliah->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_persetujuan" value="setuju">
                                        <button type="submit" class="btn btn-success btn-sm">Setuju</button>
                                    </form>
                                    <form action="{{ route('matakuliah.persetujuan', $matakuliah->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_persetujuan" value="tidak setuju">
                                        <button type="submit" class="btn btn-danger btn-sm">Tidak Setuju</button>
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

        <!-- Tabel Persetujuan Enrolment Mahasiswa -->
        <div class="card">
            <div class="card-body">
                <h3>Persetujuan Pendaftaran Mahasiswa</h3>
                <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Diploma</th>
                            <th>Prodi</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kode Matakuliah</th>
                            <th>Mata Kuliah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enrolments as $key => $enrolment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $matakuliah->prodi->kode_prodi }}</td>
                                <td>{{ $matakuliah->prodi->nama_prodi }}</td>
                                <td>{{ $enrolment->mahasiswa->nama ?? 'Tidak ditemukan' }}</td>
                                <td>{{ $matakuliah->kode_mk }}</td>
                                <td>{{ $enrolment->matakuliah->nama_matakuliah }}</td>
                                <td>
                                    <span class="badge bg-{{ $enrolment->status === 'setuju' ? 'success' : ($enrolment->status === 'tidak setuju' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($enrolment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('enrolment.approve', $enrolment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="setuju">
                                        <button type="submit" class="btn btn-success btn-sm">Setuju</button>
                                    </form>
                                    <form action="{{ route('enrolment.approve', $enrolment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="tidak setuju">
                                        <button type="submit" class="btn btn-danger btn-sm">Tidak Setuju</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada mahasiswa yang mendaftar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
