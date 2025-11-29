<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Matakuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <x-header></x-header>
    <div class="container mt-4">
        @forelse($matakuliahs as $matakuliah)
            <div class="mb-5">

                <h2 class="text-primary fw-bold"><i class="fas fa-graduation-cap"></i>
                    {{ $matakuliah->nama_matakuliah }}</h2>
                <p class="text-muted">Materi, Quiz, dan Tugas untuk mata kuliah ini.</p>
                <!-- Tampilkan pesan sukses -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tampilkan pesan error -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Tampilkan semua error validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @forelse($matakuliah->assignments as $assignment)
                    <div class="card shadow-sm p-4 mb-4"
                        style="border-left: 5px solid {{ $assignment->jenis_tugas == 'materi' ? '#0d6efd' : ($assignment->jenis_tugas == 'quiz' ? '#ffc107' : '#198754') }}; 
           border: 2px solid {{ $assignment->jenis_tugas == 'materi' ? '#0d6efd' : ($assignment->jenis_tugas == 'quiz' ? '#ffc107' : '#198754') }};">
                        <h4 id="assignment-{{ $assignment->id }}" class="fw-bold text-dark">
                            <i class="fas {{ $assignment->jenis_tugas == 'materi' ? 'fa-book' : ($assignment->jenis_tugas == 'quiz' ? 'fa-file-alt' : 'fa-tasks') }}"></i>
                            {{ $assignment->judul }}
                        </h4>
                        
                        <p class="text-secondary">{{ $assignment->deskripsi }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <span
                                class="badge bg-{{ $assignment->jenis_tugas == 'materi' ? 'primary' : ($assignment->jenis_tugas == 'quiz' ? 'warning text-dark' : 'success') }}">
                                {{ ucfirst($assignment->jenis_tugas) }}
                            </span>

                            @if ($assignment->jenis_tugas != 'materi')
                                <span
                                    class="badge {{ $assignment->deadline && \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'bg-danger' : 'bg-success' }}">
                                    <i class="fas fa-calendar-alt"></i> Deadline:
                                    {{ \Carbon\Carbon::parse($assignment->deadline)->translatedFormat('d F Y H:i') }}
                                </span>
                            @endif
                        </div>

                        @if ($assignment->file)
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $assignment->file) }}"
                                    class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-file-download"></i> {{ $assignment->original_name }}
                                </a>

                            </div>
                        @endif

                        
                        @if ($assignment->jenis_tugas == 'tugas')
                            @php
                                $submission = $assignment->submissions
                                    ->where('id_mahasiswa', auth()->user()->mahasiswa->id ?? null)
                                    ->first();
                            @endphp

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Status Pengumpulan</th>
                                        <td>
                                            @if (!$submission)
                                                <span class="badge bg-warning">Belum dikumpulkan</span>
                                            @else
                                                @if ($submission->status == 'submitted')
                                                    <span class="badge bg-success">Sudah dikumpul</span>
                                                @elseif ($submission->status == 'graded')
                                                    <span class="badge bg-primary">Dinilai</span>
                                                @elseif ($submission->status == 'late')
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($submission)
                                        <tr>
                                            <th>Batas Waktu</th>
                                            <td>{{ \Carbon\Carbon::parse($submission->assignment->deadline)->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Tanggal Pengumpulan</th>
                                            <td>{{ \Carbon\Carbon::parse($submission->submitted_at)->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Tugas yang Dikumpul</th>
                                            <td>
                                                <a href="{{ asset('storage/' . $submission->file) }}"
                                                    class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-file"></i> {{ $submission->original_name ?? 'Lihat File' }}
                                                </a>
                                        
                                                @if ($submission && $submission->status != 'graded')
                                                    <form action="{{ route('submissions.destroy', $submission->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger ms-2"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus submission ini?');">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <th>Catatan Mahasiswa</th>
                                            <td>
                                                @if ($submission->catatan)
                                                    {{ $submission->catatan }}
                                                @else
                                                    <span class="text-muted">Tidak ada catatan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="mt-4">
                                @if (!$submission)
                                    <button class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#uploadModal{{ $assignment->id }}">
                                        <i class="fas fa-upload"></i> Unggah Tugas
                                    </button>
                                @elseif ($submission && $submission->status != 'graded')

                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $assignment->id }}">
                                        <i class="fas fa-edit"></i> Edit Tugas
                                    </button>
                                    
                                    


                                    @if ($submission)
                                        <div class="modal fade" id="editModal{{ $assignment->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel" aria-hidden="true">

                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Tugas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('submissions.update', $submission->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="file" class="form-label">Ganti File
                                                                    Tugas</label>
                                                                <input type="file" name="file"
                                                                    class="form-control">
                                                                <small class="text-muted">File sebelumnya: <a
                                                                        href="{{ asset('storage/' . $submission->file) }}"
                                                                        target="_blank">
                                                                        {{ basename($submission->file) }}
                                                                    </a></small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="catatan" class="form-label">Catatan</label>
                                                                <textarea name="catatan" class="form-control" rows="3">{{ $submission->catatan }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @elseif($assignment->jenis_tugas == 'quiz')
                            @if($assignment->quiz)
                                <div class="mt-4">
                                    <a href="{{ route('quizzes.mahasiswa', ['id_quiz' => $assignment->quiz->id]) }}"
                                        class="btn btn-warning">
                                        <i class="fas fa-play"></i> Mulai {{ $assignment->quiz->judul }}
                                    </a>
                                </div>
                            @else
                                <div class="mt-4 text-danger">
                                    <i class="fas fa-exclamation-circle"></i> Quiz belum dibuat
                                </div>
                            @endif
                        @endif
                        
                        
                    </div>
                    <!-- Modal Upload Tugas -->
                    <div class="modal fade" id="uploadModal{{ $assignment->id }}" tabindex="-1"
                        aria-labelledby="uploadModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="uploadModalLabel">Pengumpulan Tugas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form action="{{ route('submissions.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_mahasiswa"
                                        value="{{ auth()->user()->mahasiswa->id ?? '' }}">

                                    <input type="hidden" name="id_assignment" value="{{ $assignment->id }}">

                                    <div class="modal-body">
                                        <label class="form-label">File Tugas</label>
                                        <input type="file" name="file" class="form-control mb-3" required>
                                        <label class="form-label">Catatan Tambahan</label>
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan (opsional)"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-upload"></i> Unggah Tugas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                @empty
                    <div class="card p-4">
                        <h3 class="mt-3 text-secondary">Belum ada tugas untuk mata kuliah ini.</h3>
                    </div>
                @endforelse
            </div>
        @empty
            <div class="container mt-4">
                <h2 class="text-primary">Tidak ada mata kuliah tersedia.</h2>
            </div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
