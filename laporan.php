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
            max-width: 1000px;
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

        /* --- KONFIGURASI PRINT UNIVERSAL --- */
        @media print {
            @page {
                size: auto;
                /* Browser akan menyesuaikan dengan pilihan kertas di setting printer */
                margin: 1.5cm;
                /* Memberikan ruang aman di tepi kertas agar tidak terpotong */
            }

            body {
                background-color: white !important;
                margin: 0;
                padding: 0;
            }

            /* Hilangkan elemen yang tidak perlu dicetak */
            .d-print-none,
            .navbar,
            .sidebar,
            #sidebar-wrapper {
                display: none !important;
            }

            /* Pastikan konten utama melebar penuh */
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

            /* Memaksa warna muncul di PDF/Printer */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Pengaturan Tabel agar tidak terpotong (Responsive Table) */
            .table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: auto !important;
                /* Tabel akan menyesuaikan kolom dengan lebar kertas */
                font-size: 11px;
                /* Ukuran font standar laporan agar muat di ukuran kertas kecil seperti Letter */
            }

            .table th,
            .table td {
                border: 1px solid #333 !important;
                /* Border lebih kontras saat diprint */
                padding: 6px !important;
                word-wrap: break-word;
            }

            /* Sembunyikan denda jika 0 agar laporan lebih bersih */
            .denda-nol {
                color: transparent !important;
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
                <p class="text-muted text-uppercase small" style="letter-spacing: 2px;">
                    Sistem Informasi E-Perpus
                </p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-violet-header text-center">
                        <tr>
                            <th width="3%">No</th>
                            <th>Nama Anggota</th>
                            <th width="15%">WhatsApp</th>
                            <th>Judul Buku</th>
                            <th width="15%">Tgl Pinjam</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = "SELECT peminjaman.*, buku.judul, anggota.nama, anggota.no_telp 
                                FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                                ORDER BY tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_array($query)) {
                            $badge_class = ($row['status'] == 'pinjam') ? 'badge-pinjam' : 'badge-kembali';
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="fw-bold"><?= $row['nama']; ?></td>
                                <td class="text-center"><?= $row['no_telp']; ?></td>
                                <td><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-2 py-1 <?= $badge_class; ?>" style="font-size: 10px;">
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
                    <i class="bi bi-printer"></i> Tanggal Cetak: <?= date('d/m/Y H:i'); ?>
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