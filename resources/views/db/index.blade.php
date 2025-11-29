<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/wiz.css">
    <link rel="stylesheet" href="css/user.css">
</head>

<body class="bg-light">
    <x-header></x-header>
    <div class="container-fluid d-flex">
        <div id="sidebar" class="card p-3 shadow-sm border-primary">
            <!-- Tombol toggle dipindahkan ke sini -->
            <button id="toggleSidebar" class="btn btn-outline-primary toggle-btn">
                <i class="fas fa-bars"></i>
            </button>

            <h3 class="mb-3"><i class="fas fa-users"></i> Users </h3>

            <h6 class="mt-2"><i class="fas fa-user-graduate text-primary"></i> Mahasiswa Online:</h6>
            <ul id="mahasiswa-online" class="list-group small"></ul>

            <h6 class="mt-3"><i class="fas fa-user-graduate text-muted"></i> Mahasiswa Offline:</h6>
            <ul id="mahasiswa-offline" class="list-group small"></ul>

            <h6 class="mt-3"><i class="fas fa-chalkboard-teacher text-primary"></i> Dosen Online:</h6>
            <ul id="dosen-online" class="list-group small"></ul>

            <h6 class="mt-3"><i class="fas fa-chalkboard-teacher text-muted"></i> Dosen Offline:</h6>
            <ul id="dosen-offline" class="list-group small"></ul>
        </div>



        <!-- Main Content -->
        <div class="content-wrapper flex-grow-1 p-4">

            <h1 id="greetingText" class="mb-4">
                <i class="fas fa-user"></i> Hi, {{ Auth::user()->mahasiswa->nama ?? Auth::user()->name }} 👋
            </h1>

            
            <!-- Timeline -->
<div class="card p-3 mb-4 shadow-sm border-success">
    <h3><i class="fas fa-calendar-alt"></i> Timeline</h3>

    @if ($assignments->isNotEmpty())
        <ul class="list-group">
            @php
                $lastDate = null;
            @endphp

            @foreach ($assignments as $assignment)
                @php
                    $assignmentDate = \Carbon\Carbon::parse($assignment->deadline)->format('l, j F Y');
                @endphp

                @if ($lastDate !== $assignmentDate)
                    <li class="list-group-item bg-light fw-bold">
                        {{ $assignmentDate }}
                    </li>
                    @php
                        $lastDate = $assignmentDate;
                    @endphp
                @endif

                <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                    <div>
                        <i class="fas fa-tasks"></i>
                        <strong>
                            <a href="{{ $assignment->jenis_tugas == 'quiz'
                                ? route('quizzes.mahasiswa', ['id_quiz' => $assignment->quiz->id])
                                : ($assignment->jenis_tugas == 'tugas'
                                    ? route('coursematakuliah', ['id_matakuliah' => $assignment->id_matakuliah]) . '#assignment-' . $assignment->id
                                    : '#') }}"
                                class="text-primary text-decoration-none">
                                {{ $assignment->judul }}
                            </a>
                        </strong>
                        <br>
                        <span class="text-dark">Assignment due:
                            {{ $assignment->matakuliah->kode_mk }}/{{ $assignment->matakuliah->nama_matakuliah }}</span>
                    </div>
                    <span>
                        <small>
                            {{ \Carbon\Carbon::parse($assignment->deadline)->format('H:i') }}
                        </small>
                        @if (\Carbon\Carbon::parse($assignment->deadline)->isPast())
                            <span class="badge bg-danger">Overdue</span>
                        @else
                            <span class="badge bg-warning">Upcoming</span>
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted p-3"><i class="fas fa-info-circle"></i> Tugas tidak tersedia.</p>
    @endif
</div>




            <!-- Recently Accessed Courses -->
            <div class="card p-3 shadow-sm border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-book"></i> Recently Accessed Courses</h3>
                    <div>
                        <button id="scrollLeft" class="btn btn-light me-2"><i class="fas fa-chevron-left"></i></button>
                        <button id="scrollRight" class="btn btn-light"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="overflow-x-auto mt-3" style="white-space: nowrap;">
                    <div id="courseContainer" class="d-flex gap-3" style="scroll-behavior: smooth;">
                        @forelse($matakuliahs as $matakuliah)
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-graduation-cap"></i>
                                        {{ $matakuliah->prodi->kode_prodi }} - {{ $matakuliah->prodi->nama_prodi }}</h5>
                                    <p class="card-text">Kode:
                                        {{ $matakuliah->kode_mk }}/{{ $matakuliah->nama_matakuliah }}</p>
                                    <a href="{{ route('coursematakuliah', ['id_matakuliah' => $matakuliah->id]) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i> View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted"><i class="fas fa-info-circle"></i> Mata kuliah tidak tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const contentWrapper = document.querySelector(".content-wrapper");
            const toggleButton = document.getElementById("toggleSidebar");
            const toggleIcon = toggleButton.querySelector("i");

            // Set ikon default ke "<" (fa-chevron-left)
            toggleIcon.classList.remove("fa-bars");
            toggleIcon.classList.add("fa-chevron-left");

            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("sidebar-hidden");
                contentWrapper.classList.toggle("content-expanded");

                // Ubah ikon berdasarkan status sidebar
                if (sidebar.classList.contains("sidebar-hidden")) {
                    toggleIcon.classList.remove("fa-chevron-left");
                    toggleIcon.classList.add("fa-chevron-right");
                } else {
                    toggleIcon.classList.remove("fa-chevron-right");
                    toggleIcon.classList.add("fa-chevron-left");
                }
            });
        });
    </script>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("courseContainer");
            const btnLeft = document.getElementById("scrollLeft");
            const btnRight = document.getElementById("scrollRight");

            btnLeft.addEventListener("click", function() {
                container.scrollBy({
                    left: -250,
                    behavior: "smooth"
                });
            });

            btnRight.addEventListener("click", function() {
                container.scrollBy({
                    left: 250,
                    behavior: "smooth"
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const greetingText = document.getElementById("greetingText");

            if (sessionStorage.getItem("visitedBefore")) {
                greetingText.innerText = "Dashboard";
            } else {
                sessionStorage.setItem("visitedBefore", true);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const logoutButton = document.querySelector(".dropdown-item[href='/logout']");

            if (logoutButton) {
                logoutButton.addEventListener("click", function() {
                    sessionStorage.removeItem("visitedBefore"); // Hapus session
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("/api/users-status")
                .then(response => response.json())
                .then(data => {
                    updateList("mahasiswa-online", data.mahasiswa_online);
                    updateList("mahasiswa-offline", data.mahasiswa_offline);
                    updateList("dosen-online", data.dosen_online);
                    updateList("dosen-offline", data.dosen_offline);
                })
                .catch(error => console.error("Error fetching user status:", error));
        });

        function updateList(id, users) {
            const listElement = document.getElementById(id);
            listElement.innerHTML = "";

            if (users.length === 0) {
                listElement.innerHTML = "<li class='list-group-item text-muted'>Tidak ada pengguna</li>";
                return;
            }

            users.forEach(user => {
                const li = document.createElement("li");
                li.classList.add("list-group-item");
                li.innerHTML = `${user.nama}`;
                listElement.appendChild(li);
            });
        }
    </script>


</body>

</html>
