<?php
session_start();
include 'koneksi.php';

// Proteksi: Hanya siswa yang boleh mengakses
if (!isset($_SESSION['level']) || $_SESSION['level'] != "siswa") {
    header("location:index.php");
    exit;
}

$nama_siswa = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pinjam - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0edf8;
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

        .badge-selesai {
            background-color: #b1a1e5;
            color: #1e0e60;
            font-weight: bold;
        }

        .badge-aktif {
            background-color: #e9b321;
            color: #1e0e60;
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
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-violet mb-0">Riwayat Peminjaman</h3>
                    <p class="text-muted small mb-0">Arsip seluruh transaksi buku Anda.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-violet-header text-center text-uppercase small">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Detail Buku</th>
                            <th class="py-3">Tgl Pinjam</th>
                            <th class="py-3">Tgl Kembali</th>
                            <th class="py-3">Denda</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        // Query diperketat: Pastikan semua kolom yang dipanggil ada di SELECT
                        // Urutkan berdasarkan kolom urutan ke-1 (biasanya ID) secara DESC
                        $sql = "SELECT p.*, b.judul, b.genre 
                                FROM peminjaman p
                                JOIN buku b ON p.id_buku = b.id_buku 
                                JOIN anggota a ON p.id_anggota = a.id_anggota
                                WHERE a.nama = '$nama_siswa' 
                                ORDER BY p.tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        if (!$query) {
                            echo "<tr><td colspan='6' class='text-center text-danger'>Gagal memuat data: " . mysqli_error($conn) . "</td></tr>";
                        } elseif (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada histori peminjaman.</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_array($query)) {
                                // PERBAIKAN LOGIKA ERROR BARIS 112:
                                // Kita cek apakah key 'tgl_kembali' ada, jika tidak ada pakai string kosong
                                $val_tgl_kembali = isset($row['tgl_kembali']) ? $row['tgl_kembali'] : '';

                                if ($val_tgl_kembali == '0000-00-00' || empty($val_tgl_kembali)) {
                                    $tgl_kembali_txt = '<span class="text-muted small">Belum Kembali</span>';
                                } else {
                                    $tgl_kembali_txt = date('d M Y', strtotime($val_tgl_kembali));
                                }
                        ?>
                                <tr>
                                    <td class="text-center text-muted small"><?= $no++; ?></td>
                                    <td>
                                        <div class="fw-bold text-violet"><?= $row['judul']; ?></div>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.7rem;"><?= $row['genre']; ?></span>
                                    </td>
                                    <td class="text-center small"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                                    <td class="text-center small fw-bold text-secondary"><?= $tgl_kembali_txt; ?></td>
                                    <td class="text-center fw-bold">
                                        <?php
                                        $denda = isset($row['denda']) ? $row['denda'] : 0;
                                        if ($denda > 0): ?>
                                            <span class="text-danger">Rp <?= number_format($denda, 0, ',', '.'); ?></span>
                                        <?php else: ?>
                                            <span class="text-success">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] == 'kembali'): ?>
                                            <span class="badge rounded-pill badge-selesai px-3 py-2">SELESAI</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill badge-aktif px-3 py-2">DIPINJAM</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>