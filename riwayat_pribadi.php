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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0edf8;
            /* Latar Dull Lavender muda */
            font-family: 'Poppins', sans-serif;
        }

        .container-content {
            background: white;
            padding: 35px;
            border-radius: 20px;
            margin-top: 30px;
            border: none;
        }

        /* Header Tabel - Violent Violet */
        .table-violet-header {
            background-color: #1e0e60;
            color: white;
        }

        /* Teks Utama - Violent Violet */
        .text-violet {
            color: #1e0e60;
        }

        /* Badge Status - Fuel Yellow */
        .badge-fuel {
            background-color: #e9b321;
            color: #1e0e60;
            padding: 8px 15px;
        }

        /* Teks Denda & Batas - Cosmic */
        .text-cosmic {
            color: #743454;
        }

        .bg-cosmic-light {
            background-color: #fceef2;
            color: #743454;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container pb-5">
        <div class="container-content shadow-lg">
            <div class="mb-4 d-flex align-items-center">
                <div class="me-3 fs-1 text-violet">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-violet mb-0">Buku yang Sedang Saya Pinjam</h3>
                    <p class="text-muted small mb-0">Halo, <span class="fw-bold"><?= $_SESSION['nama']; ?></span>. Berikut adalah daftar pinjaman aktif Anda.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-violet-header text-center text-uppercase small">
                        <tr>
                            <th class="py-3">Judul Buku</th>
                            <th class="py-3">Tanggal Pinjam</th>
                            <th class="py-3">Batas Kembali</th>
                            <th class="py-3">Denda Saat Ini</th>
                            <th class="py-3">Status</th>
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
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                    <i class='bi bi-info-circle'></i> Anda tidak memiliki pinjaman aktif saat ini.
                                  </td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                            $tgl_deadline = $row['tgl_kembali_seharusnya'];
                            $tgl_sekarang = date('Y-m-d');

                            // Hitung denda berjalan
                            $denda_berjalan = 0;
                            $terlambat = false;
                            if (strtotime($tgl_sekarang) > strtotime($tgl_deadline)) {
                                $selisih = (strtotime($tgl_sekarang) - strtotime($tgl_deadline)) / (60 * 60 * 24);
                                $denda_berjalan = $selisih * 1000; // Rp 1.000 per hari
                                $terlambat = true;
                            }
                        ?>
                            <tr>
                                <td class="fw-bold text-violet"><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="px-2 py-1 rounded <?= $terlambat ? 'bg-cosmic-light' : ''; ?>">
                                        <?= date('d/m/Y', strtotime($tgl_deadline)); ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold <?= $terlambat ? 'text-cosmic' : 'text-success'; ?>">
                                    <?= ($denda_berjalan > 0) ? "Rp " . number_format($denda_berjalan, 0, ',', '.') : "-"; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill badge-fuel">
                                        <i class="bi bi-clock-history"></i> <?= strtoupper($row['status']); ?>
                                    </span>
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