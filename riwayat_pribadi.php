<?php
session_start();
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Proteksi: Hanya siswa yang boleh masuk
if (!isset($_SESSION['level']) || $_SESSION['level'] != "siswa") {
    header("location:index.php");
    exit;
}

// Ambil pengaturan default dari admin sebagai cadangan
$res_set = mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1");
$default_set = mysqli_fetch_assoc($res_set);
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
            font-family: 'Poppins', sans-serif;
        }

        .container-content {
            background: white;
            padding: 35px;
            border-radius: 20px;
            margin-top: 30px;
            border: none;
        }

        .table-violet-header {
            background-color: #1e0e60;
            color: white;
        }

        .text-violet {
            color: #1e0e60;
        }

        .badge-fuel {
            background-color: #e9b321;
            color: #1e0e60;
            padding: 8px 15px;
        }

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
                            <th class="py-3">Denda Terakumulasi</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nama_siswa = $_SESSION['nama'];
                        $sql = "SELECT p.*, b.judul 
                                FROM peminjaman p
                                JOIN buku b ON p.id_buku = b.id_buku 
                                JOIN anggota a ON p.id_anggota = a.id_anggota
                                WHERE a.nama = '$nama_siswa' AND p.status = 'pinjam'";

                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                    <i class='bi bi-info-circle'></i> Anda tidak memiliki pinjaman aktif saat ini.
                                  </td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                            // 1. Logika Batas Kembali (Menyesuaikan inputan max hari admin)
                            $tgl_pinjam = $row['tgl_pinjam'];
                            $batas = ($row['batas_hari'] > 0) ? $row['batas_hari'] : $default_set['batas_hari_pinjam'];
                            $deadline = date('Y-m-d', strtotime($tgl_pinjam . " +$batas days"));

                            $tgl_sekarang = date('Y-m-d');

                            // 2. Hitung Denda (Menyesuaikan nominal denda dari admin)
                            $denda_total = 0;
                            $is_telat = false;

                            if (strtotime($tgl_sekarang) > strtotime($deadline)) {
                                $selisih = strtotime($tgl_sekarang) - strtotime($deadline);
                                $hari_telat = floor($selisih / (60 * 60 * 24));

                                // Gunakan denda dari transaksi, jika kosong gunakan default pengaturan
                                $biaya_per_hari = ($row['denda_per_hari'] > 0) ? $row['denda_per_hari'] : $default_set['denda_per_hari'];

                                $denda_total = $hari_telat * $biaya_per_hari;
                                $is_telat = true;
                            }
                        ?>
                            <tr>
                                <td class="fw-bold text-violet"><?= $row['judul']; ?></td>
                                <td class="text-center small"><?= date('d/m/Y', strtotime($tgl_pinjam)); ?></td>
                                <td class="text-center">
                                    <span class="px-2 py-1 rounded small <?= $is_telat ? 'bg-cosmic-light' : 'text-success fw-bold'; ?>">
                                        <?= date('d/m/Y', strtotime($deadline)); ?>
                                        <?= $is_telat ? ' (Terlambat)' : ''; ?>
                                    </span>
                                </td>
                                <td class="text-center fw-bold <?= $is_telat ? 'text-cosmic' : 'text-muted small'; ?>">
                                    <?= ($denda_total > 0) ? "Rp " . number_format($denda_total, 0, ',', '.') : "Belum ada denda"; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill badge-fuel text-uppercase" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock-history"></i> <?= $row['status']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <small class="text-muted">* Segera kembalikan buku sebelum jatuh tempo untuk menghindari denda tambahan.</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>