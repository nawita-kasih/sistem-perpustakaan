<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya siswa yang boleh masuk sini
if ($_SESSION['level'] != "siswa") {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Buku Saya - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h3>Buku yang Sedang Saya Pinjam</h3>
        <p class="text-muted">Halo, <?= $_SESSION['nama']; ?>. Berikut adalah daftar pinjaman aktif Anda.</p>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-primary">
                <tr>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nama_siswa = $_SESSION['nama'];
                // Query JOIN untuk mengambil buku berdasarkan nama siswa yang login
                $sql = "SELECT peminjaman.*, buku.judul 
                        FROM peminjaman 
                        JOIN buku ON peminjaman.id_buku = buku.id_buku 
                        JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                        WHERE anggota.nama = '$nama_siswa' AND peminjaman.status = 'pinjam'";

                $query = mysqli_query($conn, $sql);

                if (mysqli_num_rows($query) == 0) {
                    echo "<tr><td colspan='4' class='text-center'>Anda tidak sedang meminjam buku.</td></tr>";
                }

                while ($row = mysqli_fetch_array($query)) {
                    // Logika sederhana: Batas kembali adalah 7 hari setelah pinjam
                    $tgl_pinjam = $row['tgl_pinjam'];
                    $tgl_kembali = date('Y-m-d', strtotime($tgl_pinjam . ' + 7 days'));
                ?>
                    <tr>
                        <td><?= $row['judul']; ?></td>
                        <td><?= $tgl_pinjam; ?></td>
                        <td><strong class="text-danger"><?= $tgl_kembali; ?></strong></td>
                        <td><span class="badge bg-warning text-dark">Belum Dikembalikan</span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>