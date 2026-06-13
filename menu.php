<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Logika untuk menentukan halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    body {
        display: flex;
        min-height: 100vh;
        overflow-x: hidden;
        flex-direction: column;
        /* Tambahan untuk layout mobile */
    }

    /* Sidebar Styling */
    .sidebar {
        width: 280px;
        background-color: #1e0e60;
        /* Violent Violet */
        color: white;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
        z-index: 1050;
        /* Diperbesar agar menutupi elemen lain di mobile */
        padding-top: 25px;
        transition: all 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
    }

    .sidebar-brand {
        padding: 0 25px 25px;
        font-size: 2.4rem;
        font-weight: bold;
        letter-spacing: 1px;
    }

    /* Menu Container */
    .sidebar-nav-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sidebar-nav-container::-webkit-scrollbar {
        display: none;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.7);
        padding: 14px 25px;
        margin-bottom: 4px;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: 0.3s;
        border-left: 4px solid transparent;
    }

    .sidebar .nav-link i {
        font-size: 1.2rem;
        margin-right: 15px;
    }

    /* Hover Effect */
    .sidebar .nav-link:hover {
        color: #e9b321;
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* ACTIVE MENU STYLE */
    .sidebar .nav-link.active {
        color: #e9b321 !important;
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 4px solid #e9b321;
    }

    .sidebar-bottom {
        margin-top: auto;
        padding: 20px 25px;
    }

    .sidebar-info-card {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(177, 161, 229, 0.2);
        border-radius: 15px;
        padding: 20px 15px;
        margin-bottom: 15px;
        text-align: center;
    }

    .btn-logout-sidebar {
        background-color: #e9b321;
        color: #1e0e60;
        border: none;
        font-weight: bold;
        width: 100%;
        padding: 12px;
        transition: 0.3s;
    }

    .btn-logout-sidebar:hover {
        background-color: #b1a1e5;
        color: #1e0e60;
    }

    /* Main Content Area Styling */
    .main-content {
        margin-left: 280px;
        width: calc(100% - 280px);
        padding: 30px;
        transition: all 0.3s ease-in-out;
    }

    /* =========================================
       TAMBAHAN CSS RESPONSIVE MOBILE
       ========================================= */
    .mobile-header {
        display: none;
        background-color: #ffffff;
        padding: 15px 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }

    @media (max-width: 992px) {
        .mobile-header {
            display: flex;
            /* Munculkan header atas di HP */
        }

        .sidebar {
            left: -280px;
            /* Sembunyikan sidebar ke kiri */
        }

        .sidebar.show-mobile {
            left: 0;
            /* Munculkan sidebar saat tombol diklik */
        }

        .sidebar-overlay.show-mobile {
            display: block;
            /* Munculkan efek gelap */
        }

        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 15px;
            /* Kurangi padding agar tidak sempit di HP */
        }
    }
</style>

<div class="mobile-header d-lg-none">
    <div class="fs-4 fw-bold">
        <span style="color: #e9b321;">Librar</span><span style="color: #1e0e60;">ify</span>
    </div>
    <button class="btn border-0 shadow-sm" id="btnToggleSidebar" style="background-color: #1e0e60; color: white; border-radius: 10px;">
        <i class="bi bi-list fs-4"></i>
    </button>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar shadow-lg" id="sidebarMenu">
    <div class="sidebar-brand text-center text-md-start d-none d-lg-block">
        <span style="color: #e9b321;">Librar</span>ify
    </div>

    <div class="sidebar-nav-container nav flex-column mt-2">
        <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
            <i class="bi bi-house-door"></i> Dashboard
        </a>

        <?php if ($_SESSION['level'] == 'admin') : ?>
            <div class="small fw-bold text-uppercase px-4 mt-4 mb-2" style="color: #b1a1e5; opacity: 0.6; font-size: 0.75rem;">Admin Menu</div>

            <a class="nav-link <?= ($current_page == 'buku.php') ? 'active' : ''; ?>" href="buku.php">
                <i class="bi bi-book"></i> Kelola Buku
            </a>

            <a class="nav-link <?= ($current_page == 'tambah_anggota.php') ? 'active' : ''; ?>" href="tambah_anggota.php">
                <i class="bi bi-people"></i> Kelola Siswa
            </a>

            <a class="nav-link <?= ($current_page == 'data_pinjam.php') ? 'active' : ''; ?>" href="data_pinjam.php">
                <i class="bi bi-arrow-left-right"></i> Transaksi Aktif
            </a>

            <a class="nav-link <?= ($current_page == 'laporan.php') ? 'active' : ''; ?>" href="laporan.php">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>
        <?php endif; ?>

        <?php if ($_SESSION['level'] == 'siswa') : ?>
            <div class="small fw-bold text-uppercase px-4 mt-4 mb-2" style="color: #b1a1e5; opacity: 0.6; font-size: 0.75rem;">Siswa Menu</div>

            <a class="nav-link <?= ($current_page == 'riwayat_pribadi.php') ? 'active' : ''; ?>" href="riwayat_pribadi.php">
                <i class="bi bi-journal-bookmark"></i> Buku Saya
            </a>

            <a class="nav-link <?= ($current_page == 'riwayat_pinjam.php') ? 'active' : ''; ?>" href="riwayat_pinjam.php">
                <i class="bi bi-clock-history"></i> Riwayat Pinjam
            </a>

            <a class="nav-link <?= ($current_page == 'profil.php') ? 'active' : ''; ?>" href="profil.php">
                <i class="bi bi-person-circle"></i> Info Akun
            </a>
        <?php endif; ?>
    </div>

    <div class="sidebar-bottom">
        <div class="sidebar-info-card shadow-sm">
            <div class="mb-2">
                <i class="bi bi-book-half" style="font-size: 2rem; color: #b1a1e5;"></i>
            </div>
            <h6 class="fw-bold mb-1" style="color: #e9b321;">Librarify v1.0</h6>
            <p class="small text-white-50 mb-0" style="font-size: 0.7rem;">Sistem Informasi Perpustakaan Digital Terpadu.</p>
        </div>

        <a class="btn btn-logout-sidebar rounded-pill d-block text-center" href="logout.php" onclick="konfirmasiLogout(event, this.href)">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SCRIPT LOGOUT
        function konfirmasiLogout(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin Ingin Keluar?',
                text: "Sesi Anda akan diakhiri dan harus login kembali untuk masuk.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#1e0e60',
                confirmButtonText: '<i class="bi bi-box-arrow-right"></i> Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // SCRIPT TOGGLE MENU MOBILE
        document.addEventListener("DOMContentLoaded", function() {
            const btnToggle = document.getElementById('btnToggleSidebar');
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');

            if (btnToggle) {
                btnToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show-mobile');
                    overlay.classList.toggle('show-mobile');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show-mobile');
                    overlay.classList.remove('show-mobile');
                });
            }
        });
    </script>