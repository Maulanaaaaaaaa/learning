<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/megumin.css">
</head>

<body>
    
    <x-header></x-header>
    <div class="container my-4">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#tambahTugasModal">Tambah Tugas</button>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h3>Buat Tugas</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Dosen</th>
                                <th>Nama Matakuliah</th>
                                <th>Nama Tugas</th>
                                <th>Deskripsi</th>
                                <th>Jenis Tugas</th>
                                <th>Deadline</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $key => $assignment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $assignment->dosen->nama ?? 'Tidak ada' }}</td>
                                    <td>{{ $assignment->matakuliah->kode_mk }} - {{ $assignment->matakuliah->nama_matakuliah }}</td>
                                    <td>{{ $assignment->judul }}</td>
                                    <td>{{ $assignment->deskripsi }}</td>
                                    <td>{{ ucfirst($assignment->jenis_tugas) }}</td>
                                    <td>{{ $assignment->deadline ? date('Y-m-d H:i', strtotime($assignment->deadline)) : '-' }}</td>
                                    <td>{{ $assignment->room->nama_ruangan ?? 'Tidak ada' }}</td>
                                    <td>
                                        @if ($assignment->schedule)
                                            {{ \Carbon\Carbon::parse($assignment->schedule->tanggal)->format('Y-m-d') }} - 
                                            {{ ucfirst($assignment->schedule->hari) }} - 
                                            {{ \Carbon\Carbon::parse($assignment->schedule->waktu_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($assignment->schedule->waktu_selesai)->format('H:i') }}
                                        @else
                                            Tidak ada jadwal
                                        @endif
                                    </td>
                                    <td>
                                        @if ($assignment->file)
                                            <a href="{{ Storage::url($assignment->file) }}" class="btn btn-info btn-sm" target="_blank">
                                                {{ $assignment->original_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-tugas" data-id="{{ $assignment->id }}" data-bs-toggle="modal" data-bs-target="#editTugasModal">Edit</button>
                                        <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</button>
                                        </form>
                                        @if ($assignment->jenis_tugas == 'quiz')
                                            <a href="{{ route('quizzes.index', ['assignmentId' => $assignment->id]) }}" class="btn btn-primary btn-sm">Kelola Quiz</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data tugas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tugas -->
    <div class="modal fade" id="tambahTugasModal" tabindex="-1" aria-labelledby="tambahTugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('assignments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahTugasModalLabel">Tambah Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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
                            <label for="id_matakuliah" class="form-label">Matakuliah</label>
                            <select name="id_matakuliah" class="form-control" required>
                                <option value="">Pilih Matakuliah</option>
                                @foreach (App\Models\Matakuliah::all() as $matakuliah)
                                    <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_matakuliah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_tugas" class="form-label">Jenis Tugas</label>
                            <select name="jenis_tugas" class="form-control" required>
                                <option value="">Pilih Tugas</option>
                                <option value="materi">Materi</option>
                                <option value="quiz">Quiz</option>
                                <option value="tugas">Tugas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="use_schedule_deadline_tambah">
                            <label class="form-check-label" for="use_schedule_deadline_tambah">Gunakan Jadwal sebagai Deadline</label>
                        </div>
                        <div class="mb-3">
                            <label for="id_room" class="form-label">Ruangan</label>
                            <select name="id_room" class="form-control" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_schedule" class="form-label">Jadwal</label>
                            <select name="id_schedule" class="form-control" id="id_schedule_tambah">
                                <option value="">Pilih Jadwal</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ \Carbon\Carbon::parse($schedule->tanggal)->format('Y-m-d') }} - 
                                        {{ ucfirst($schedule->hari) }} - 
                                        {{ \Carbon\Carbon::parse($schedule->waktu_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->waktu_selesai)->format('H:i') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File (PDF/DOCX/PPT)</label>
                            <input type="file" class="form-control" name="file" accept=".pdf,.docx,.ppt,.pptx">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div class="modal fade" id="editTugasModal" tabindex="-1" aria-labelledby="editTugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTugasForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTugasModalLabel">Edit Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_tugas_id" name="id">
                        <div class="mb-3">
                            <label for="edit_id_matakuliah" class="form-label">Matakuliah</label>
                            <select id="edit_id_matakuliah" name="id_matakuliah" class="form-control" required>
                                <option value="">Pilih Matakuliah</option>
                                @foreach (App\Models\Matakuliah::all() as $matakuliah)
                                    <option value="{{ $matakuliah->id }}">{{ $matakuliah->kode_mk }} - {{ $matakuliah->nama_matakuliah }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jenis_tugas" class="form-label">Jenis Tugas</label>
                            <select id="edit_jenis_tugas" name="jenis_tugas" class="form-control" required>
                                <option value="materi">Materi</option>
                                <option value="quiz">Quiz</option>
                                <option value="tugas">Tugas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deadline" class="form-label">Deadline</label>
                            <input type="datetime-local" class="form-control" id="edit_deadline" name="deadline">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="use_schedule_deadline">
                            <label class="form-check-label" for="use_schedule_deadline">Gunakan Jadwal sebagai Deadline</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_room" class="form-label">Ruangan</label>
                            <select id="edit_id_room" name="id_room" class="form-control" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_schedule" class="form-label">Jadwal</label>
                            <select id="edit_id_schedule" name="id_schedule" class="form-control">
                                <option value="">Pilih Jadwal</option>
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ \Carbon\Carbon::parse($schedule->tanggal)->format('Y-m-d') }} - 
                                        {{ ucfirst($schedule->hari) }} - 
                                        {{ \Carbon\Carbon::parse($schedule->waktu_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->waktu_selesai)->format('H:i') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_file" class="form-label">File Tugas</label>
                            <input type="file" class="form-control" id="edit_file" name="file" accept=".pdf,.docx,.ppt,.pptx">
                        </div>
                        <div class="mb-3" id="currentFileContainer">
                            <label class="form-label">File Saat Ini:</label>
                            <p id="currentFile">
                                <a href="#" target="_blank" id="currentFileLink">Tidak ada file</a>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Logika untuk modal tambah tugas
    let scheduleSelectTambah = document.getElementById('id_schedule_tambah');
    let deadlineInputTambah = document.getElementById('deadline');
    let useScheduleDeadlineCheckboxTambah = document.getElementById('use_schedule_deadline_tambah');

    // Fungsi untuk mengisi deadline berdasarkan jadwal (tanggal dan waktu_selesai)
    function updateDeadlineFromScheduleTambah() {
        let selectedOption = scheduleSelectTambah.options[scheduleSelectTambah.selectedIndex];
        if (selectedOption && selectedOption.value) {
            let scheduleParts = selectedOption.textContent.split(' - '); // Pecah teks opsi jadwal
            let scheduleDate = scheduleParts[0].trim(); // Ambil tanggal (indeks 0)
            let scheduleEndTime = scheduleParts[3].trim(); // Ambil waktu_selesai (indeks 3)
            let dateTimeValue = `${scheduleDate}T${scheduleEndTime}`; // Gabungkan tanggal dan waktu_selesai
            deadlineInputTambah.value = dateTimeValue; // Set nilai input datetime-local
        }
    }

    // Jika checkbox dicentang, deadline mengikuti jadwal (waktu_selesai)
    useScheduleDeadlineCheckboxTambah.addEventListener('change', function() {
        if (this.checked) {
            updateDeadlineFromScheduleTambah();
            deadlineInputTambah.setAttribute('readonly', true); // Membuat input deadline readonly
        } else {
            deadlineInputTambah.removeAttribute('readonly'); // Membuat input deadline bisa diisi manual
        }
    });

    // Saat jadwal berubah, update deadline jika checkbox dicentang
    scheduleSelectTambah.addEventListener('change', function() {
        if (useScheduleDeadlineCheckboxTambah.checked) {
            updateDeadlineFromScheduleTambah();
        }
    });

    // Logika untuk modal edit tugas
    let scheduleSelectEdit = document.getElementById('edit_id_schedule');
    let deadlineInputEdit = document.getElementById('edit_deadline');
    let useScheduleDeadlineCheckboxEdit = document.getElementById('use_schedule_deadline');

    // Fungsi untuk mengisi deadline berdasarkan jadwal (tanggal dan waktu_selesai)
    function updateDeadlineFromScheduleEdit() {
        let selectedOption = scheduleSelectEdit.options[scheduleSelectEdit.selectedIndex];
        if (selectedOption && selectedOption.value) {
            let scheduleParts = selectedOption.textContent.split(' - '); // Pecah teks opsi jadwal
            let scheduleDate = scheduleParts[0].trim(); // Ambil tanggal (indeks 0)
            let scheduleEndTime = scheduleParts[3].trim(); // Ambil waktu_selesai (indeks 3)
            let dateTimeValue = `${scheduleDate}T${scheduleEndTime}`; // Gabungkan tanggal dan waktu_selesai
            deadlineInputEdit.value = dateTimeValue; // Set nilai input datetime-local
        }
    }

    // Jika checkbox dicentang, deadline mengikuti jadwal (waktu_selesai)
    useScheduleDeadlineCheckboxEdit.addEventListener('change', function() {
        if (this.checked) {
            updateDeadlineFromScheduleEdit();
            deadlineInputEdit.setAttribute('readonly', true); // Membuat input deadline readonly
        } else {
            deadlineInputEdit.removeAttribute('readonly'); // Membuat input deadline bisa diisi manual
        }
    });

    // Saat jadwal berubah, update deadline jika checkbox dicentang
    scheduleSelectEdit.addEventListener('change', function() {
        if (useScheduleDeadlineCheckboxEdit.checked) {
            updateDeadlineFromScheduleEdit();
        }
    });

    // Saat modal edit dibuka, pastikan checkbox dan deadline diatur dengan benar
    document.querySelectorAll('.edit-tugas').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');

            fetch(`/buattugas/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Isi data ke form
                    document.getElementById('edit_tugas_id').value = data.id;
                    document.getElementById('edit_judul').value = data.judul;
                    document.getElementById('edit_deskripsi').value = data.deskripsi;
                    document.getElementById('edit_id_matakuliah').value = data.id_matakuliah;
                    document.getElementById('edit_jenis_tugas').value = data.jenis_tugas;
                    document.getElementById('edit_deadline').value = data.deadline ?? '';
                    document.getElementById('edit_id_room').value = data.id_room;
                    document.getElementById('edit_id_schedule').value = data.id_schedule;

                    // Cek apakah deadline sama dengan tanggal dan waktu_selesai jadwal
                    let selectedOption = scheduleSelectEdit.options[scheduleSelectEdit.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        let scheduleParts = selectedOption.textContent.split(' - ');
                        let scheduleDate = scheduleParts[0].trim();
                        let scheduleEndTime = scheduleParts[3].trim();
                        let scheduleDateTime = `${scheduleDate}T${scheduleEndTime}`;

                        if (data.deadline === scheduleDateTime) {
                            useScheduleDeadlineCheckboxEdit.checked = true;
                            deadlineInputEdit.setAttribute('readonly', true);
                        } else {
                            useScheduleDeadlineCheckboxEdit.checked = false;
                            deadlineInputEdit.removeAttribute('readonly');
                        }
                    }

                    // Tampilkan file yang sudah ada
                    let fileContainer = document.getElementById("currentFileContainer");
                    let fileLink = document.getElementById("currentFileLink");
                    if (data.file) {
                        fileLink.href = `/storage/${data.file}`;
                        fileLink.textContent = "Lihat File Saat Ini";
                        fileContainer.style.display = "block";
                    } else {
                        fileLink.textContent = "Tidak ada file";
                        fileContainer.style.display = "none";
                    }
                    

                    document.getElementById('editTugasForm').setAttribute('action', `/buattugas/${data.id}`);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan saat mengambil data tugas!');
                });
        });
    });
});
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>