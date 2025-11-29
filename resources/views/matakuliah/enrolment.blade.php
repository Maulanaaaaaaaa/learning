<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolment Dosen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <x-header></x-header>
    <div class="container mt-5">
        <h2>Enrolment Mahasiswa - Dosen</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>Kode MK</th>
                    <th>Status</th>
                    <th>Persetujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enrolments as $key => $enrolment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $enrolment->mahasiswa->nama }}</td>
                    <td>{{ $enrolment->matakuliah->nama }}</td>
                    <td>{{ $enrolment->matakuliah->kode_mk }}</td>
                    <td>
                        <!-- Status Mahasiswa -->
                        <form action="{{ route('enrolments.toggleStatus', $enrolment->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $enrolment->status === 'terdaftar' ? 'btn-success' : 'btn-secondary' }}">
                                {{ ucfirst($enrolment->status) }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <!-- Persetujuan Dosen -->
                        <form action="{{ route('enrolments.updateApproval', $enrolment->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="status_persetujuan" value="disetujui" class="btn btn-sm btn-success {{ $enrolment->status_persetujuan === 'disetujui' ? 'disabled' : '' }}">
                                ✔️ Setujui
                            </button>
                            <button type="submit" name="status_persetujuan" value="ditolak" class="btn btn-sm btn-danger {{ $enrolment->status_persetujuan === 'ditolak' ? 'disabled' : '' }}">
                                ❌ Tolak
                            </button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" disabled>Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data enrolment</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
