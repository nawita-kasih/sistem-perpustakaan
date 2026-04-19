<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya siswa yang boleh masuk
if (!isset($_SESSION['level']) || $_SESSION['level'] != "siswa") {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Buku Saya - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="container-content shadow-sm">
            <div class="mb-4">
                <h3 class="fw-bold">Buku yang Sedang Saya Pinjam</h3>
                <p class="text-muted">Halo, <?= $_SESSION['nama']; ?>. Berikut adalah daftar pinjaman aktif Anda.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul Buku</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Batas Kembali</th>
                            <th class="text-center">Denda Saat Ini</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nama_siswa = $_SESSION['nama'];
                        // Query JOIN menggunakan Nama dari Session untuk keamanan data
                        $sql = "SELECT peminjaman.*, buku.judul 
                                FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                                WHERE anggota.nama = '$nama_siswa' AND peminjaman.status = 'pinjam'";

                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Anda tidak memiliki pinjaman aktif.</td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                            $tgl_deadline = $row['tgl_kembali_seharusnya'];
                            $tgl_sekarang = date('Y-m-d');

                            // Hitung denda berjalan (simulasi tampilan untuk siswa)
                            $denda_berjalan = 0;
                            if ($tgl_sekarang > $tgl_deadline) {
                                $selisih = (strtotime($tgl_sekarang) - strtotime($tgl_deadline)) / (60 * 60 * 24);
                                $denda_berjalan = $selisih * 1000; // Rp 1.000 per hari
                            }
                        ?>
                            <tr>
                                <td class="fw-bold"><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="text-danger fw-bold"><?= date('d/m/Y', strtotime($tgl_deadline)); ?></span>
                                </td>
                                <td class="text-center text-danger">
                                    <?= ($denda_berjalan > 0) ? "Rp " . number_format($denda_berjalan, 0, ',', '.') : "-"; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">PINJAM</span>
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