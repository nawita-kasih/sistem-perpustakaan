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
        z-index: 1000;
        padding-top: 20px;
        transition: all 0.3s;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.7);
        padding: 12px 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: 0.3s;
        border-left: 4px solid transparent;
        /* Border sembunyi */
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

    /* ACTIVE MENU STYLE (Sesuai gambar yang diinginkan) */
    .sidebar .nav-link.active {
        color: #e9b321 !important;
        /* Fuel Yellow */
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 4px solid #e9b321;
        /* Garis indikator di kiri */
    }

    .sidebar-brand {
        padding: 0 25px 30px;
        font-size: 1.8rem;
        font-weight: bold;
    }

    /* Main Content Area Styling */
    .main-content {
        margin-left: 280px;
        width: calc(100% - 280px);
        padding: 30px;
    }

    .btn-logout-sidebar {
        position: absolute;
        bottom: 20px;
        left: 25px;
        right: 25px;
        background-color: #e9b321;
        color: #1e0e60;
        border: none;
        font-weight: bold;
    }

    @media (max-width: 992px) {
        .sidebar {
            margin-left: -280px;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .sidebar.active {
            margin-left: 0;
        }
    }
</style>

<div class="sidebar shadow-lg">
    <div class="sidebar-brand">
        <span style="color: #e9b321;">E</span>-Perpus
    </div>

    <div class="nav flex-column mt-2">
        <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
            <i class="bi bi-house-door"></i> Dashboard
        </a>

        <?php if ($_SESSION['level'] == 'admin') : ?>
            <div class="small fw-bold text-uppercase px-4 mt-3 mb-2" style="color: #b1a1e5; opacity: 0.6;">Admin Menu</div>

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
            <div class="small fw-bold text-uppercase px-4 mt-3 mb-2" style="color: #b1a1e5; opacity: 0.6;">Siswa Menu</div>

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

        <a class="btn btn-logout-sidebar rounded-pill" href="logout.php" onclick="return confirm('Keluar sekarang?')">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">