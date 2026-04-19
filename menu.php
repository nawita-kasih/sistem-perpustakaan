<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fs-3 fw-bold" href="index.php">
            <span class="text-primary">E</span>-Perpus
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Dashboard</a>
                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <a class="nav-link" href="buku.php"><i class="bi bi-book"></i> Kelola Buku</a>
                    <a class="nav-link" href="tambah_anggota.php"><i class="bi bi-people"></i> Kelola Siswa</a>
                    <a class="nav-link" href="data_pinjam.php"><i class="bi bi-arrow-left-right"></i> Transaksi Aktif</a>
                    <a class="nav-link" href="laporan.php"><i class="bi bi-file-earmark-text"></i> Laporan</a>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'siswa') : ?>
                    <a class="nav-link" href="riwayat_pribadi.php"><i class="bi bi-journal-bookmark"></i> Buku Saya</a>
                    <a class="nav-link" href="profil.php"><i class="bi bi-person-circle"></i> Info Akun</a>
                <?php endif; ?>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="btn btn-outline-danger btn-sm px-3 rounded-pill" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right"></i> Logout (<?= $_SESSION['nama']; ?>)
                </a>
            </div>
        </div>
    </div>
</nav>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">