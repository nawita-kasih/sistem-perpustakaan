<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}
include 'koneksi.php';

// FITUR SEARCH: Menangkap keyword pencarian
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
            padding: 8px 15px;
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

    <div class="container mt-4">
        <div class="container-content shadow-lg">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0 fw-bold text-violet">Riwayat Peminjaman Aktif</h3>
                    <p class="text-muted small">Daftar buku yang sedang dipinjam oleh siswa</p>
                </div>
                <a href="index.php" class="btn btn-outline-lavender btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Form
                </a>
            </div>

            <div class="search-container shadow-sm">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="cari" class="form-control border-start-0 ps-0"
                                placeholder="Cari berdasarkan nama siswa atau judul buku..."
                                value="<?= htmlspecialchars($keyword); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-fuel w-100 shadow-sm">CARI DATA</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark-custom text-center">
                        <tr>
                            <th class="py-3">Nama Anggota</th>
                            <th class="py-3">Kontak (WA)</th>
                            <th class="py-3">Judul Buku</th>
                            <th class="py-3">Tgl Pinjam</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Modifikasi Query untuk mendukung fitur Search
                        $sql = "SELECT peminjaman.*, buku.judul, anggota.nama, anggota.no_telp 
                                FROM peminjaman 
                                JOIN buku ON peminjaman.id_buku = buku.id_buku 
                                JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
                                WHERE status = 'pinjam'";

                        // Jika ada keyword, tambahkan filter pencarian
                        if ($keyword != '') {
                            $sql .= " AND (anggota.nama LIKE '%$keyword%' OR buku.judul LIKE '%$keyword%')";
                        }

                        $sql .= " ORDER BY tgl_pinjam DESC";

                        $query = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($query) == 0) {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Data tidak ditemukan atau tidak ada peminjaman aktif.</td></tr>";
                        }

                        while ($row = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td class="fw-bold text-violet"><?= $row['nama']; ?></td>
                                <td class="text-center">
                                    <a href="https://wa.me/<?= $row['no_telp']; ?>" target="_blank"
                                        class="btn btn-sm btn-fuel rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-whatsapp"></i> Hubungi
                                    </a>
                                </td>
                                <td><?= $row['judul']; ?></td>
                                <td class="text-center"><?= date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill badge-cosmic">
                                        <i class="bi bi-clock"></i> <?= strtoupper($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="proses_kembali.php?id=<?= $row['id_pinjam']; ?>&id_buku=<?= $row['id_buku']; ?>"
                                        class="btn btn-sm px-3 rounded-pill fw-bold shadow-sm"
                                        style="background-color: #1e0e60; color: #e9b321;"
                                        onclick="return confirm('Konfirmasi pengembalian buku ini?')">
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