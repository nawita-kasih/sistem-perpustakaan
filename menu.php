<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand fs-3 fw-bold" href="index.php">E-Perpus</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">Daftar Buku</a>

                <?php if ($_SESSION['level'] == 'admin') : ?>
                    <a class="nav-link" href="buku.php">Kelola Buku</a>
                    <a class="nav-link" href="data_pinjam.php">Proses Transaksi</a>
                    <a class="nav-link" href="laporan.php">Laporan</a>
                <?php endif; ?>

                <?php if ($_SESSION['level'] == 'siswa') : ?>
                    <a class="nav-link" href="riwayat_pribadi.php">Buku Saya</a>
                <?php endif; ?>
            </div>

            <div class="navbar-nav ms-auto">
                <a class="nav-link btn btn-danger btn-sm text-white" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">
                    Logout (<?= $_SESSION['nama']; ?>)
                </a>
            </div>
        </div>
    </div>
</nav>