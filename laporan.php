<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}
include 'koneksi.php';
// Mengatur timezone agar sesuai WIB
date_default_timezone_set('Asia/Jakarta');

// Ambil pengaturan default sebagai cadangan jika data di tabel peminjaman masih kosong
$res_set = mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1");
$default_set = mysqli_fetch_assoc($res_set);
$def_batas = $default_set['batas_hari_pinjam'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0edf8;
            font-family: 'Poppins', sans-serif;
        }

        .card-laporan {
            background: white;
            padding: 40px;
            border-radius: 20px;
            border: none;
            max-width: 1300px;
            margin: auto;
        }

        .header-line {
            border-bottom: 3px solid #1e0e60;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .table-violet-header {
            background-color: #1e0e60 !important;
            color: white !important;
        }

        .badge-pinjam {
            background-color: #743454 !important;
            color: white !important;
        }

        .badge-kembali {
            background-color: #b1a1e5 !important;
            color: #1e0e60 !important;
        }

        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }

            .d-print-none,
            .navbar,
            .sidebar,
            #sidebar-wrapper {
                display: none !important;
            }

            .container,
            .container-fluid,
            .main-content {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card-laporan {
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 8.5px;
            }

            .table th,
            .table td {
                border: 1px solid #333 !important;
                padding: 4px !important;
            }
        }

        .btn-print {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="card-laporan shadow-lg">
            <div class="text-center header-line">
                <h2 class="fw-bold mb-0" style="color: #1e0e60;">LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
                <p class="text-muted text-uppercase small" style="letter-spacing: 2px;">Sistem Informasi E-Perpus</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-violet-header text-center small text-uppercase">
                        <tr>
                            <th width="3%">No</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th width="9%">Tgl Pinjam</th>
                            <th width="9%">Deadline</th>
                            <th width="9%">Tgl Kembali</th>
                            <th width="8%">Denda</th>
                            <th width="7%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Query ditambahkan b.pengarang agar bisa ditampilkan
                        $sql = "SELECT p.*, b.judul, b.pengarang, a.nama, a.kelas, u.username 
                                FROM peminjaman p 
                                JOIN buku b ON p.id_buku = b.id_buku 
                                JOIN anggota a ON p.id_anggota = a.id_anggota
                                JOIN users u ON a.nama = u.nama_lengkap
                                ORDER BY p.tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_array($query)) {
                            $status = strtolower($row['status']);
                            $badge_class = ($status == 'pinjam') ? 'badge-pinjam' : 'badge-kembali';

                            $tgl_pinjam = $row['tgl_pinjam'];
                            $batas = ($row['batas_hari'] > 0) ? $row['batas_hari'] : $def_batas;
                            $deadline = date('Y-m-d', strtotime($tgl_pinjam . " +$batas days"));

                            $tgl_kembali_raw = isset($row['tgl_kembali']) ? $row['tgl_kembali'] : '';

                            // Logika Tanggal Kembali & Perhitungan Denda
                            if ($status == 'pinjam') {
                                $tgl_kembali_tampil = '<span class="text-muted small"><i>Belum Kembali</i></span>';

                                $tgl_skrg = date('Y-m-d');
                                if (strtotime($tgl_skrg) > strtotime($deadline)) {
                                    $selisih = strtotime($tgl_skrg) - strtotime($deadline);
                                    $hari_telat = floor($selisih / (60 * 60 * 24));
                                    $biaya_harian = ($row['denda_per_hari'] > 0) ? $row['denda_per_hari'] : $default_set['denda_per_hari'];
                                    $nominal_denda = $hari_telat * $biaya_harian;
                                    $denda_tampil = "<b class='text-danger'>Rp " . number_format($nominal_denda, 0, ',', '.') . "</b>";
                                } else {
                                    $denda_tampil = "-";
                                }
                            } else {
                                // KONDISI SUDAH KEMBALI
                                // Jika tgl_kembali di DB kosong, kita pakai tgl_skrg sebagai cadangan agar tidak muncul strip
                                if (!empty($row['tgl_kembali']) && $row['tgl_kembali'] != '0000-00-00') {
                                    $tgl_kembali_tampil = date('d/m/Y', strtotime($row['tgl_kembali']));
                                } else {
                                    // Jika status 'kembali' tapi tanggal kosong di DB, tampilkan tgl hari ini (saat diklik)
                                    $tgl_kembali_tampil = date('d/m/Y');
                                }

                                $denda_tampil = ($row['denda'] > 0) ? "Rp " . number_format($row['denda'], 0, ',', '.') : "-";
                            }
                        ?>
                            <tr>
                                <td class="text-center small"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-bold" style="color: #1e0e60;"><?= $row['nama']; ?></div>
                                    <div class="small text-muted"><?= $row['username']; ?> | <?= $row['kelas']; ?></div>
                                </td>

                                <td class="small">
                                    <div class="fw-bold"><?= $row['judul']; ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem; font-style: italic;">
                                        <i class="bi bi-person-fill"></i> <?= $row['pengarang']; ?>
                                    </div>
                                </td>

                                <td class="text-center small"><?= date('d/m/Y', strtotime($tgl_pinjam)); ?></td>
                                <td class="text-center small text-danger fw-bold"><?= date('d/m/Y', strtotime($deadline)); ?></td>
                                <td class="text-center small text-success fw-bold"><?= $tgl_kembali_tampil; ?></td>
                                <td class="text-center small"><?= $denda_tampil; ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-2 py-1 <?= $badge_class; ?>" style="font-size: 8px;">
                                        <?= strtoupper($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5">
                <div class="text-muted small">
                    <i class="bi bi-printer"></i> Tanggal Cetak: <?= date('d/m/Y H:i'); ?> WIB
                </div>
                <button onclick="window.print()" class="btn btn-print d-print-none px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>