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
    <title>Kelola Buku - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-custom {
            background: white;
            border-radius: 12px;
            border: none;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-custom shadow-sm">
                    <div class="card-header bg-dark text-white p-3">
                        <h5 class="mb-0">Tambah Buku Baru</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses_buku.php" method="POST">
                            <input type="hidden" name="aksi" value="tambah">
                            <div class="mb-3">
                                <label class="form-label">Judul Buku</label>
                                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul..." required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Stok Awal</label>
                                <input type="number" name="stok" class="form-control" placeholder="0" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 shadow-sm">Simpan Buku</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom shadow-sm">
                    <div class="card-header bg-dark text-white p-3">
                        <h5 class="mb-0">Daftar Koleksi Buku</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Judul Buku</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
                                    if (mysqli_num_rows($query) == 0) {
                                        echo "<tr><td colspan='4' class='text-center py-4'>Belum ada koleksi buku.</td></tr>";
                                    }
                                    while ($b = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td class="ps-4 text-muted small">#<?= $b['id_buku']; ?></td>
                                            <td class="fw-bold"><?= $b['judul']; ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary"><?= $b['stok']; ?></span>
                                            </td>
                                            <td class="text-center px-4">
                                                <a href="proses_buku.php?aksi=hapus&id=<?= $b['id_buku']; ?>"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                                    Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>