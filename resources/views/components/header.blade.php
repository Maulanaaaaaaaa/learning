<header
    style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #00ced1; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); 
    position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
    <div class="header-left" style="display: flex; align-items: center; gap: 20px;">
        <span style="font-size: 20px; font-weight: bold; color: #333;">Learningml</span>

        @if (Auth::check())
            <nav style="display: flex; gap: 20px;">
                @if (Auth::user()->role === 'admin')
                    <a href="/admin" class="nav-link" data-link="/admin"
                        style="text-decoration: none; color: #333; font-size: 14px;">Dashboard</a>
                    <a href="/kelola" class="nav-link" data-link="/kelola"
                        style="text-decoration: none; color: #333; font-size: 14px;">Kelola Mahasiswa</a>
                    <a href="/keloladosen" class="nav-link" data-link="/keloladosen"
                        style="text-decoration: none; color: #333; font-size: 14px;">Kelola Dosen</a>
                    <a href="/kelolaprodi" class="nav-link" data-link="/kelolaprodi"
                        style="text-decoration: none; color: #333; font-size: 14px;">Kelola prodi</a>
                    <a href="/kelolamatkul" class="nav-link" data-link="/kelolamatkul"
                        style="text-decoration: none; color: #333; font-size: 14px;">Kelola Matakuliah</a>
                        <a href="/buatkelas" class="nav-link" data-link="/buatkelas"
                        style="text-decoration: none; color: #333; font-size: 14px;">Buat Kelas</a>
                        <a href="/rooms" class="nav-link" data-link="/rooms"
                        style="text-decoration: none; color: #333; font-size: 14px;">Jadwal Kelas</a>
                        <a href="/mahasiswarooms" class="nav-link" data-link="/mahasiswarooms"
                        style="text-decoration: none; color: #333; font-size: 14px;">Mahasiswa Kelas</a>
                    
                @elseif (Auth::user()->role === 'mahasiswa')
                    <a href="/homes" class="nav-link" data-link="/homes"
                        style="text-decoration: none; color: #333; font-size: 14px;">Home</a>
                    <a href="/index" class="nav-link" data-link="/index"
                        style="text-decoration: none; color: #333; font-size: 14px;">Dashboard</a>
                    <a href="#" class="nav-link" data-link="#"
                        style="text-decoration: none; color: #333; font-size: 14px;">Course</a>
                @elseif (Auth::user()->role === 'dosen')
                    <a href="/dosen" class="nav-link" data-link="/dosen"
                        style="text-decoration: none; color: #333; font-size: 14px;">Dashboard</a>
                    <a href="/kelolamatakuliah" class="nav-link" data-link="/kelolamatakuliah"
                        style="text-decoration: none; color: #333; font-size: 14px;">Persetujuan Matakuliah</a>
                    <a href="/buattugas" class="nav-link" data-link="/buattugas"
                        style="text-decoration: none; color: #333; font-size: 14px;">Tambah Materi Tugas dan Quiz</a>
                        <a href="/submission" class="nav-link" data-link="/submission"
                        style="text-decoration: none; color: #333; font-size: 14px;">Nilai Tugas</a>
                @endif
            </nav>
        @endif
    </div>

    <div class="header-right" style="display: flex; align-items: center; gap: 10px;">
        <div class="user-info dropdown" style="position: relative;">
            <button class="user-button" onclick="toggleProfileMenu()"
                style="background: none; border: none; font-size: 16px; font-weight: bold; color: #333; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                {{ Auth::user()->nama }}
                <span id="arrow">▼</span>
            </button>
            <div id="profileMenu" class="dropdown-menu"
                style="display: none; position: absolute; top: 40px; right: 0; background-color: white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); border-radius: 4px; overflow: hidden; z-index: 10;">
                <a href="#" class="dropdown-item"
                    style="display: block; padding: 10px 20px; text-decoration: none; color: #333; font-size: 14px; border-bottom: 1px solid #ddd;">Ganti
                    Password</a>
                <a href="/logout" class="dropdown-item"
                    style="display: block; padding: 10px 20px; text-decoration: none; color: #333; font-size: 14px;">Keluar</a>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        const arrow = document.getElementById('arrow');
        const isHidden = menu.style.display === 'none' || menu.style.display === '';
        menu.style.display = isHidden ? 'block' : 'none';
        arrow.textContent = isHidden ? '▲' : '▼';
    }

    document.addEventListener('click', (event) => {
        const menu = document.getElementById('profileMenu');
        const userButton = event.target.closest('.user-button');
        if (!userButton && menu.style.display === 'block') {
            menu.style.display = 'none';
            document.getElementById('arrow').textContent = '▼';
        }
    });

    function highlightActiveLink() {
        const links = document.querySelectorAll('.nav-link');
        const currentPath = window.location.pathname;
        links.forEach(link => {
            if (link.getAttribute('data-link') === currentPath) {
                link.style.backgroundColor = '#ffffff';
            } else {
                link.style.backgroundColor = '';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', highlightActiveLink);
</script>

<style>
    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        /* Sembunyikan scroll */
    }

    body {
        padding-top: 60px;
        /* Memberi ruang agar konten tidak tertutup header */
        overflow-y: auto;
        /* Aktifkan scroll tetapi tetap tersembunyi */
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* Internet Explorer & Edge */
    }

    /* Untuk Chrome, Safari, dan Opera */
    body::-webkit-scrollbar {
        display: none;
    }
</style>
