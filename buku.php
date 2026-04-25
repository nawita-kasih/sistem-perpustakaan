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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0edf8;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .btn-primary {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #b1a1e5;
            color: #1e0e60;
            transform: translateY(-2px);
        }

        .table-custom-header {
            background-color: #1e0e60;
            color: white;
        }

        .btn-cosmic {
            background-color: #743454;
            color: white;
            border: none;
        }

        .btn-cosmic:hover {
            background-color: #1e0e60;
            color: white;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #b1a1e5;
        }

        .badge-stok {
            background-color: #e9b321;
            color: #1e0e60;
        }

        .badge-genre {
            background-color: #b1a1e5;
            color: #1e0e60;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-4">
        <div class="row align-items-start">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold text-center mb-4" style="color: #1e0e60;">
                        <i class="bi bi-journal-plus"></i> Tambah Koleksi
                    </h5>
                    <form action="proses_buku.php" method="POST">
                        <input type="hidden" name="aksi" value="tambah">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">JUDUL BUKU</label>
                            <input type="text" name="judul" class="form-control border-0 bg-light" placeholder="Masukkan judul..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">GENRE BUKU</label>
                            <select name="genre" class="form-select border-0 bg-light" required>
                                <option value="">Pilih Genre...</option>
                                <optgroup label="Akademik">
                                    <option value="Informatika">Informatika</option>
                                    <option value="Sains">Sains</option>
                                    <option value="Bahasa">Bahasa</option>
                                    <option value="Ekonomi">Ekonomi</option>
                                </optgroup>
                                <optgroup label="Fiksi">
                                    <option value="Novel">Novel</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Action">Action</option>
                                    <option value="Slice of Life">Slice of Life</option>
                                    <option value="Fantasi">Fantasi</option>
                                </optgroup>
                                <optgroup label="Umum">
                                    <option value="Biografi">Biografi</option>
                                    <option value="Sejarah">Sejarah</option>
                                    <option value="Motivasi">Motivasi</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">PENGARANG</label>
                            <input type="text" name="pengarang" class="form-control border-0 bg-light" placeholder="Nama penulis..." required>
                        </div>
                        <div class="row">
                            <div class="col-7 mb-3">
                                <label class="form-label small fw-bold">PENERBIT</label>
                                <input type="text" name="penerbit" class="form-control border-0 bg-light" placeholder="Penerbit..." required>
                            </div>
                            <div class="col-5 mb-3">
                                <label class="form-label small fw-bold">TAHUN</label>
                                <input type="number" name="tahun_terbit" class="form-control border-0 bg-light" placeholder="YYYY" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">STOK AWAL</label>
                            <input type="number" name="stok" class="form-control border-0 bg-light" placeholder="0" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
                            <i class="bi bi-save"></i> SIMPAN BUKU
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h5 class="fw-bold mb-4" style="color: #1e0e60;">Daftar Inventaris Buku</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-custom-header text-center">
                                <tr>
                                    <th class="py-3">INFORMASI BUKU</th>
                                    <th class="py-3">GENRE</th>
                                    <th class="py-3">STOK</th>
                                    <th class="py-3">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
                                if (mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Belum ada koleksi buku.</td></tr>";
                                }
                                while ($b = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        <td>
                                            <strong style="color: #1e0e60;"><?= $b['judul']; ?></strong><br>
                                            <small class="text-muted">
                                                <?= $b['pengarang']; ?> | <?= $b['penerbit']; ?>
                                                (<?= $b['tahun_terbit']; ?>)
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill badge-genre px-2 py-1">
                                                <?= $b['genre']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill badge-stok px-3 py-2">
                                                <?= $b['stok']; ?> Unit
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="proses_buku.php?aksi=hapus&id=<?= $b['id_buku']; ?>"
                                                class="btn btn-cosmic btn-sm rounded-circle shadow-sm"
                                                onclick="return confirm('Hapus buku ini dari katalog?')">
                                                <i class="bi bi-trash"></i>
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