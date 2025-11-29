<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas & Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/megumin.css">
</head>

<body>
    <x-header></x-header>
    <div class="container mt-5">
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
        {{-- Modal Tambah Kelas --}}
        <div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahKelasModalLabel">Tambah Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('rooms.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="id_matakuliah" class="form-label">Mata Kuliah</label>
                                    <select name="id_matakuliah" id="id_matakuliah" class="form-select" required>
                                        <option value="">Pilih Mata Kuliah</option>
                                        @foreach ($matakuliahs as $matakuliah)
                                            <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} -
                                                {{ $matakuliah->nama_matakuliah }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="id_kelas" class="form-label">Kelas</label>
                                    <select name="id_kelas" id="id_kelas" class="form-select" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach (App\Models\Kelass::all() as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                                    <input type="text" class="form-control" name="nama_ruangan">
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kelas" class="form-label">Jenis Kelas</label>
                                    <select name="jenis_kelas" class="form-select" required>
                                        <option value="offline">Offline</option>
                                        <option value="online">Online</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Tambah Kelas</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Kelas --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- Button Tambah Kelas --}}
                    <h2>Daftar Kelas</h2>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#tambahKelasModal">
                        Tambah Kelas
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>MataKuliah</th>
                                <th>Nama Kelas</th>
                                <th>Nama Ruangan</th>
                                <th>Offline/Online</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rooms as $key => $room)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $room->matakuliah->kode_mk }} - {{ $room->matakuliah->nama_matakuliah }}</td>
                                    <td>{{ $room->kelas->nama_kelas }}</td>
                                    <td>{{ $room->nama_ruangan }}</td>
                                    <td>{{ $room->jenis_kelas }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm me-2 editRoomBtn"
                                            data-id="{{ $room->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editKelasModal">
                                            Edit
                                        </button>
                                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada data kelas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Edit Kelas --}}
        <div class="modal fade" id="editKelasModal" tabindex="-1" aria-labelledby="editKelasModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKelasModalLabel">Edit Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editKelasForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editRoomId" name="id">
                            <div class="mb-3">
                                <label for="editIdMatakuliah" class="form-label">Mata Kuliah</label>
                                <select name="id_matakuliah" id="editIdMatakuliah" class="form-select" required>
                                    <option value="">Pilih Mata Kuliah</option>
                                    @foreach ($matakuliahs as $matakuliah)
                                        <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} -
                                            {{ $matakuliah->nama_matakuliah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editIdKelas" class="form-label">Kelas</label>
                                <select name="id_kelas" id="editIdKelas" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelass as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editNamaRuangan" class="form-label">Nama Ruangan</label>
                                <input type="text" class="form-control" id="editNamaRuangan" name="nama_ruangan">
                            </div>
                            <div class="mb-3">
                                <label for="editJenisKelas" class="form-label">Jenis Kelas</label>
                                <select name="jenis_kelas" id="editJenisKelas" class="form-select" required>
                                    <option value="offline">Offline</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Button Tambah Jadwal --}}
        @if ($rooms->isNotEmpty())
            <div class="mt-5">
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                    data-bs-target="#tambahJadwalModal">
                    Tambah Jadwal
                </button>
                {{-- Modal Tambah Jadwal --}}
                <div class="modal fade" id="tambahJadwalModal" tabindex="-1"
                    aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('schedules.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_room" value="{{ $room->id }}">
                                    <div class="mb-3">
                                        <label for="id_room" class="form-label">Nama Kelas</label>
                                        <select name="id_room" id="id_room" class="form-select" required>
                                            <option value="">Pilih Ruangan</option>
                                            @foreach ($rooms as $room)
                                                <option value="{{ $room->id }}">{{ $room->nama_ruangan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id_matakuliah" class="form-label">Mata Kuliah</label>
                                        <select name="id_matakuliah" id="id_matakuliah" class="form-select" required>
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach ($matakuliahs as $matakuliah)
                                                <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} -
                                                    {{ $matakuliah->nama_matakuliah }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hari" class="form-label">Hari</label>
                                        <select name="hari" id="hari" class="form-control" required>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                        <input type="time" name="waktu_mulai" id="waktu_mulai"
                                            class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                                        <input type="time" name="waktu_selesai" id="waktu_selesai"
                                            class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success mt-2">Tambah Jadwal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabel Jadwal --}}
        <h2>Daftar Jadwal</h2>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table table-striped table-hover">
                            <tr>
                                <th>No.</th>
                                <th>Nama Ruangan</th>
                                <th>Matakuliah</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $key => $schedule)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $schedule->room->nama_ruangan }}</td>
                                    <td>{{ $schedule->matakuliah->kode_mk }} -
                                        {{ $schedule->matakuliah->nama_matakuliah }}</td>
                                    <td>{{ $schedule->tanggal }}</td>
                                    <td>{{ $schedule->hari }}</td>
                                    <td>{{ $schedule->waktu_mulai }}</td>
                                    <td>{{ $schedule->waktu_selesai }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm me-2 editScheduleBtn"
                                            data-id="{{ $schedule->id }}" data-bs-toggle="modal"
                                            data-bs-target="#editJadwalModal">
                                            Edit
                                        </button>
                                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada jadwal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Edit Jadwal --}}
        <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editJadwalForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editScheduleId" name="id">
                            <div class="mb-3">
                                <label for="editIdRoom" class="form-label">Nama Kelas</label>
                                <select name="id_room" id="editIdRoom" class="form-select" required>
                                    <option value="">Pilih Ruangan</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->nama_ruangan}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="editIdMatakuliah" class="form-label">Mata Kuliah</label>
                                <select name="id_matakuliah" id="editIdMatakuliahSchedule" class="form-select"
                                    required>
                                    <option value="">Pilih Mata Kuliah</option>
                                    @foreach ($matakuliahs as $matakuliah)
                                        <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} -
                                            {{ $matakuliah->nama_matakuliah }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editTanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="editTanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="editHari" class="form-label">Hari</label>
                                <input type="text" class="form-control" id="editHari" name="hari" required>
                            </div>
                            <div class="mb-3">
                                <label for="editWaktuMulai" class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" id="editWaktuMulai" name="waktu_mulai"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editWaktuSelesai" class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" id="editWaktuSelesai"
                                    name="waktu_selesai" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk Edit Rooms dan Schedules --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fungsi untuk menangani edit Room
            document.querySelectorAll(".editRoomBtn").forEach(button => {
                button.addEventListener("click", function() {
                    const roomId = this.getAttribute("data-id");

                    fetch(`/rooms/${roomId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById("editRoomId").value = data.id;
                            document.getElementById("editIdKelas").value = data.id_kelas; // Tambahkan id_kelas
                            
                            document.getElementById("editIdMatakuliah").value = data
                                .id_matakuliah;
                                document.getElementById("editNamaRuangan").value = data.nama_ruangan ?? ''; // Jika null, set string kosong
                    document.getElementById("editJenisKelas").value = data.jenis_kelas;
                            document.getElementById("editKelasForm").setAttribute("action",
                                `/rooms/${roomId}`);
                        })
                        .catch(error => console.error("Error fetching room:", error));
                });
            });

            // Fungsi untuk menangani edit Schedule
            document.querySelectorAll(".editScheduleBtn").forEach(button => {
                button.addEventListener("click", function() {
                    const scheduleId = this.getAttribute("data-id");

                    fetch(`/rooms/schedules/${scheduleId}/edit`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Jadwal tidak ditemukan.");
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Fetched schedule data:", data);
                            document.getElementById("editScheduleId").value = data.id;
                            document.getElementById("editTanggal").value = data.tanggal;
                            // Set nilai select untuk id_room
                            document.getElementById("editIdRoom").value = data.id_room;
                            document.getElementById("editIdMatakuliahSchedule").value = data
                                .id_matakuliah;
                            document.getElementById("editHari").value = data.hari;
                            document.getElementById("editWaktuMulai").value = data.waktu_mulai;
                            document.getElementById("editWaktuSelesai").value = data
                                .waktu_selesai;
                            document.getElementById("editJadwalForm").setAttribute("action",
                                `/rooms/schedules/${scheduleId}`);
                        })
                        .catch(error => console.error("Error fetching schedule:", error));
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const inputTanggal = document.getElementById("tanggal");
            const inputHari = document.getElementById("hari");
            inputTanggal.addEventListener("change", function() {
                const tanggal = new Date(this.value);
                if (!isNaN(tanggal.getTime())) { // Pastikan input tanggal valid
                    inputHari.value = days[tanggal.getDay()];
                } else {
                    inputHari.value = "";
                }
            });
            // Logika serupa untuk Edit Jadwal
            const editTanggal = document.getElementById("editTanggal");
            const editHari = document.getElementById("editHari");
            editTanggal.addEventListener("change", function() {
                const tanggal = new Date(this.value);

                if (!isNaN(tanggal.getTime())) {
                    editHari.value = days[tanggal.getDay()];
                } else {
                    editHari.value = "";
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
