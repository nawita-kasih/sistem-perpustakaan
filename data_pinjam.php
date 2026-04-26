<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// AMBIL PENGATURAN DARI DATABASE (Batas hari & Nominal Denda)
$set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1"));
$max_hari = $set['batas_hari_pinjam'];
$biaya_denda = $set['denda_per_hari'];

$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman - E-Perpus</title>
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
            border: none;
        }

        .table-dark-custom {
            background-color: #1e0e60;
            color: white;
        }

        .btn-outline-lavender {
            color: #1e0e60;
            border: 2px solid #b1a1e5;
            font-weight: 600;
        }

        .btn-outline-lavender:hover {
            background-color: #b1a1e5;
            color: #1e0e60;
        }

        .btn-fuel {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-fuel:hover {
            background-color: #b1a1e5;
            transform: translateY(-2px);
        }

        .badge-cosmic {
            background-color: #743454;
            color: white;
            padding: 5px 12px;
            font-size: 0.7rem;
        }

        .text-violet {
            color: #1e0e60;
        }

        .search-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container-fluid mt-4 px-4">
        <div class="container-content shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0 fw-bold text-violet">Riwayat Peminjaman Aktif</h3>
                    <p class="text-muted small">
                        Kebijakan: <b>Maks <?= $max_hari; ?> Hari</b> | Denda: <b>Rp <?= number_format($biaya_denda, 0, ',', '.'); ?>/hari</b>
                    </p>
                </div>
                <a href="index.php" class="btn btn-outline-lavender btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="search-container shadow-sm">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="cari" class="form-control border-start-0 ps-0"
                                placeholder="Cari nama, username, kelas, atau judul buku..."
                                value="<?= htmlspecialchars($keyword); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-fuel w-100 shadow-sm">CARI</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark-custom text-center small text-uppercase">
                        <tr>
                            <th class="py-3">Anggota</th>
                            <th class="py-3">Buku & Penulis</th>
                            <th class="py-3">Waktu Pinjam</th>
                            <th class="py-3">Denda</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Update Query: JOIN users untuk username dan SELECT pengarang untuk penulis
                        $sql = "SELECT p.*, b.judul, b.pengarang, a.nama, a.no_telp, a.kelas, u.username 
                                FROM peminjaman p
                                JOIN buku b ON p.id_buku = b.id_buku 
                                JOIN anggota a ON p.id_anggota = a.id_anggota
                                JOIN users u ON a.nama = u.nama_lengkap
                                WHERE p.status = 'pinjam'";

                        if ($keyword != '') {
                            $sql .= " AND (a.nama LIKE '%$keyword%' OR b.judul LIKE '%$keyword%' OR u.username LIKE '%$keyword%')";
                        }

                        $sql .= " ORDER BY p.tgl_pinjam DESC";
                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Tidak ada peminjaman aktif.</td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                            $tgl_pinjam = $row['tgl_pinjam'];
                            $deadline = date('Y-m-d', strtotime($tgl_pinjam . " +$max_hari days"));
                            $tgl_sekarang = date('Y-m-d');

                            $denda_total = 0;
                            $pesan_denda = "<span class='text-muted small'>Belum ada denda</span>";

                            if (strtotime($tgl_sekarang) > strtotime($deadline)) {
                                $selisih = strtotime($tgl_sekarang) - strtotime($deadline);
                                $hari_telat = floor($selisih / (60 * 60 * 24));
                                $denda_total = $hari_telat * $biaya_denda;
                                $pesan_denda = "<span class='text-danger fw-bold'>Rp " . number_format($denda_total, 0, ',', '.') . "</span><br><small class='text-danger'>($hari_telat Hari Telat)</small>";
                            }

                            $warna_deadline = (strtotime($tgl_sekarang) > strtotime($deadline)) ? 'text-danger fw-bold' : 'text-success fw-bold';
                        ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-violet"><?= $row['nama']; ?></div>
                                    <div class="small text-muted mb-1"><?= $row['username']; ?> | <span class="badge bg-light text-dark border" style="font-size: 0.6rem;"><?= $row['kelas']; ?></span></div>
                                    <a href="https://wa.me/<?= $row['no_telp']; ?>" target="_blank" class="text-success text-decoration-none small">
                                        <i class="bi bi-whatsapp"></i> <?= $row['no_telp']; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= $row['judul']; ?></div>
                                    <small class="text-muted italic"><i class="bi bi-person-pen"></i> Penulis: <?= $row['pengarang']; ?></small>
                                </td>
                                <td class="text-center small">
                                    <div class="mb-1 text-muted">Pinjam: <?= date('d/m/Y', strtotime($tgl_pinjam)); ?></div>
                                    <div class="<?= $warna_deadline; ?>">Deadline: <?= date('d/m/Y', strtotime($deadline)); ?></div>
                                </td>
                                <td class="text-center"><?= $pesan_denda; ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill badge-cosmic uppercase"><?= $row['status']; ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="proses_kembali.php?id=<?= $row['id_pinjam']; ?>&id_buku=<?= $row['id_buku']; ?>&denda=<?= $denda_total; ?>"
                                        class="btn btn-sm px-3 rounded-pill fw-bold shadow-sm"
                                        style="background-color: #1e0e60; color: #e9b321;"
                                        onclick="return confirm('Konfirmasi pengembalian buku?')">
                                        Kembalikan
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