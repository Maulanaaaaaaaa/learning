<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/ari.css">
    <!-- Tambahkan FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <x-header></x-header>
    <div class="container">
        <h1 class="text-primary fw-bold"><i class="fas fa-graduation-cap"></i> Pilih Program Studi</h1>

        @if (App\Models\Prodi::count() > 0)
            <div class="mb-3">
                <select id="prodiSelect" class="form-control" required>
                    <option value="">Pilih Program Studi</option>
                    @foreach (App\Models\Prodi::all() as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p class="text-center text-muted">Belum ada program studi yang tersedia.</p>
        @endif

        <div id="enrolmentContainer" style="display: none;">
            @forelse ($matakuliahs as $matakuliah)
                <div class="grid matakuliah-card" data-prodi="{{ $matakuliah->id_prodi }}">
                    <!-- Card untuk deskripsi mata kuliah -->
                    <div class="card mb-4 border border-primary"> <!-- Tambahkan border color di sini -->
                        <div class="card-content">
                            <h2><i class="fas fa-book"></i> {{ $matakuliah->kode_mk }}/{{ $matakuliah->nama_matakuliah }}</h2> <!-- Tambahkan icon di sini -->
                            <p>{{ $matakuliah->deskripsi }}</p>
                            <a href="#">{{ $matakuliah->kode_mk }}/{{ $matakuliah->nama_matakuliah }}</a>
                        </div>
                    </div>

                    <!-- Card untuk Enrolment Options -->
                    <div class="card mb-4 border border-success"> <!-- Tambahkan border color di sini -->
                        <div class="card-content">
                            <h2><i class="fas fa-user-plus"></i> Enrolment options</h2> <!-- Tambahkan icon di sini -->
                            <div class="enrolment-options">
                                <h3><i class="fas fa-user-graduate"></i> Self enrolment (Student)</h3> <!-- Tambahkan icon di sini -->

                                @if ($matakuliah->status_persetujuan == 'setuju')
                                    @php
                                        $mahasiswa = Auth::user()->mahasiswa;
                                        $sudahEnroll = $matakuliah->enrolments
                                            ->where('id_mahasiswa', $mahasiswa->id ?? null)
                                            ->first();
                                    @endphp

                                    @if ($sudahEnroll)
                                        @if ($sudahEnroll->status == 'menunggu persetujuan')
                                            <span class="text-warning"><i class="fas fa-hourglass-half"></i> Menunggu persetujuan dosen</span> <!-- Tambahkan icon di sini -->
                                        @elseif ($sudahEnroll->status == 'setuju')
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Anda sudah terdaftar</span> <!-- Tambahkan icon di sini -->
                                        @elseif ($sudahEnroll->status == 'tidak setuju')
                                            <span class="text-danger"><i class="fas fa-times-circle"></i> Pendaftaran ditolak</span> <!-- Tambahkan icon di sini -->
                                        @endif
                                    @else
                                        <form action="{{ route('enroll.matakuliah', $matakuliah->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Enroll</button> <!-- Tambahkan icon di sini -->
                                        </form>
                                    @endif
                                @else
                                    <span><i class="fas fa-ban"></i> Enrolment Tidak Aktif</span> <!-- Tambahkan icon di sini -->
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <h1 class="text-center">Tidak ada mata kuliah tersedia</h1>
            @endforelse
        </div>
    </div>

    <script>
        // Fungsi untuk menyimpan pilihan program studi ke localStorage
        function saveSelectedProdi(prodiId) {
            localStorage.setItem('selectedProdi', prodiId);
        }

        // Fungsi untuk mengambil pilihan program studi dari localStorage
        function getSelectedProdi() {
            return localStorage.getItem('selectedProdi');
        }

        // Fungsi untuk memfilter dan menampilkan kartu mata kuliah
        function filterMatakuliah() {
            let selectedProdi = document.getElementById("prodiSelect").value;
            let hasVisibleCard = false;

            // Simpan pilihan program studi ke localStorage
            saveSelectedProdi(selectedProdi);

            document.querySelectorAll(".matakuliah-card").forEach(function (card) {
                if (card.dataset.prodi === selectedProdi) {
                    card.style.display = "grid"; /* Tampilkan sebagai grid */
                    hasVisibleCard = true;
                } else {
                    card.style.display = "none";
                }
            });

            let enrolmentContainer = document.getElementById("enrolmentContainer");
            let emptyMessage = document.getElementById("emptyMessage");

            if (hasVisibleCard) {
                enrolmentContainer.style.display = "block";
                if (emptyMessage) emptyMessage.remove();
            } else {
                if (!emptyMessage) {
                    emptyMessage = document.createElement("h1");
                    emptyMessage.id = "emptyMessage";
                    emptyMessage.className = "text-center mt-3";
                    // emptyMessage.textContent = "Tidak ada mata kuliah tersedia";
                    enrolmentContainer.appendChild(emptyMessage);
                }
            }
        }

        // Saat halaman dimuat, periksa apakah ada pilihan program studi yang tersimpan
        document.addEventListener("DOMContentLoaded", function () {
            let selectedProdi = getSelectedProdi();
            let prodiSelect = document.getElementById("prodiSelect");

            if (selectedProdi && prodiSelect) {
                prodiSelect.value = selectedProdi; // Set nilai dropdown
                filterMatakuliah(); // Terapkan filter
            }

            // Tambahkan event listener untuk dropdown
            if (prodiSelect) {
                prodiSelect.addEventListener("change", filterMatakuliah);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>