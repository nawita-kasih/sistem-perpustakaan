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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-laporan {
            background: white;
            padding: 40px;
            border-radius: 10px;
        }

        @media print {

            .d-print-none,
            .navbar {
                display: none !important;
            }

            .card-laporan {
                padding: 0;
                border: none;
                box-shadow: none;
            }

            body {
                background-color: white;
            }
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <div class="card-laporan shadow-sm">
            <div class="text-center mb-4">
                <h2 class="fw-bold">LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
                <p class="text-muted">Laporan aktivitas peminjaman dan pengembalian buku</p>
                <hr>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Anggota</th>
                            <th>No. Telp</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Menambahkan anggota.no_telp ke dalam query laporan
                        $sql = "SELECT peminjaman.*, buku.judul, anggota.nama, anggota.no_telp 
                                FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                                ORDER BY tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='6' class='text-center py-4'>Belum ada data transaksi.</td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                            $status_color = ($row['status'] == 'pinjam') ? 'bg-warning text-dark' : 'bg-success';
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="fw-bold"><?= $row['nama']; ?></td>
                                <td><?= $row['no_telp']; ?></td>
                                <td><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $status_color; ?> px-3">
                                        <?= strtoupper($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button onclick="window.print()" class="btn btn-dark d-print-none px-4">Cetak Laporan (PDF)</button>
            </div>
        </div>

        <footer class="text-center mt-4 mb-5 text-muted small d-print-none">
            Dicetak pada: <?= date('d F Y, H:i'); ?> WIB
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>