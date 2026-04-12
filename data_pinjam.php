<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <div class="container-content shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Riwayat Peminjaman Aktif</h3>
                    <p class="text-muted small">Daftar buku yang belum dikembalikan oleh siswa</p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Kembali ke Form</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Anggota</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Tgl Pinjam</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query JOIN
                        $sql = "SELECT peminjaman.*, buku.judul, anggota.nama 
                                FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                                WHERE status = 'pinjam'
                                ORDER BY tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Tidak ada peminjaman aktif saat ini.</td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= $row['nama']; ?></td>
                                <td><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark px-3"><?= strtoupper($row['status']); ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="proses_kembali.php?id=<?= $row['id_pinjam']; ?>&id_buku=<?= $row['id_buku']; ?>"
                                        class="btn btn-sm btn-success shadow-sm"
                                        onclick="return confirm('Yakin buku ini sudah dikembalikan?')">
                                        Kembalikan Buku
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>