<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['level'] != "admin") {
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold text-center mb-4"><i class="bi bi-journal-plus"></i> Tambah Koleksi</h5>
                    <form action="proses_buku.php" method="POST">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="mb-3"><label class="form-label small fw-bold">JUDUL BUKU</label><input type="text" name="judul" class="form-control border-0 bg-light" required></div>
                        <div class="mb-3"><label class="form-label small fw-bold">PENGARANG</label><input type="text" name="pengarang" class="form-control border-0 bg-light" required></div>
                        <div class="row">
                            <div class="col-7 mb-3"><label class="form-label small fw-bold">PENERBIT</label><input type="text" name="penerbit" class="form-control border-0 bg-light" required></div>
                            <div class="col-5 mb-3"><label class="form-label small fw-bold">TAHUN</label><input type="number" name="tahun_terbit" class="form-control border-0 bg-light" required></div>
                        </div>
                        <div class="mb-4"><label class="form-label small fw-bold">STOK AWAL</label><input type="number" name="stok" class="form-control border-0 bg-light" required></div>
                        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">SIMPAN BUKU</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4">Daftar Inventaris Buku</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>INFORMASI BUKU</th>
                                    <th>STOK</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
                                while ($b = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        <td class="text-center small">#BK-<?= $b['id_buku']; ?></td>
                                        <td><strong><?= $b['judul']; ?></strong><br><small class="text-muted"><?= $b['pengarang']; ?> | <?= $b['penerbit']; ?></small></td>
                                        <td class="text-center"><span class="badge rounded-pill bg-secondary"><?= $b['stok']; ?></span></td>
                                        <td class="text-center"><a href="proses_buku.php?aksi=hapus&id=<?= $b['id_buku']; ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Hapus buku?')"><i class="bi bi-trash"></i></a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>