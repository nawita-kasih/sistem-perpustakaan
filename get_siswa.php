<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}

$level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
$nama_user = $_SESSION['nama'];
include 'koneksi.php';

// Logika Search & Filter Katalog
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';
$genre_filter = isset($_GET['genre']) ? mysqli_real_escape_string($conn, $_GET['genre']) : '';

$sql_katalog = "SELECT * FROM buku WHERE 1=1";
if ($keyword != "") $sql_katalog .= " AND judul LIKE '%$keyword%'";
if ($genre_filter != "") $sql_katalog .= " AND genre = '$genre_filter'";
$sql_katalog .= " ORDER BY judul ASC";
$res = mysqli_query($conn, $sql_katalog);

// Ambil Statistik
if ($level == 'admin') {
    $total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
    $total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
    $pinjam_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='pinjam'"))['total'];
} else {
    $pinjam_saya_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota WHERE anggota.nama = '$nama_user' AND status='pinjam'");
    $pinjam_saya = mysqli_fetch_assoc($pinjam_saya_res)['total'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0edf8;
        }

        .welcome-section {
            background: linear-gradient(45deg, #1e0e60, #b1a1e5);
            color: white;
            padding: 40px;
            border-radius: 20px;
        }

        .card-stat {
            border: none;
            border-radius: 15px;
            color: white;
            transition: 0.3s;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .btn-custom {
            background-color: #e9b321;
            color: #1e0e60;
            font-weight: bold;
            border: none;
        }

        .btn-custom:hover {
            background-color: #b1a1e5;
            color: #1e0e60;
        }

        .badge-genre {
            background-color: #b1a1e5;
            color: #1e0e60;
            font-weight: 500;
        }

        .input-readonly {
            background-color: #e9ecef !important;
            font-weight: 600;
            color: #1e0e60;
        }

        .status-msg {
            font-size: 0.75rem;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container pb-5">
        <div class="welcome-section shadow-lg mb-4">
            <h2 class="fw-bold">Halo, <?= $nama_user; ?>! 👋</h2>
            <p class="lead mb-0">Kelola peminjaman buku dengan cepat dan mudah.</p>
        </div>

        <div class="row mb-4">
            <?php if ($level == 'admin') : ?>
                <div class="col-md-4">
                    <div class="card card-stat p-3 shadow-sm mb-3" style="background-color: #1e0e60;">
                        <h6>TOTAL BUKU</h6>
                        <h2 class="fw-bold"><?= $total_buku; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-3 shadow-sm mb-3" style="background-color: #743454;">
                        <h6>ANGGOTA</h6>
                        <h2 class="fw-bold"><?= $total_siswa; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-3 shadow-sm mb-3" style="background-color: #846940;">
                        <h6>PINJAMAN AKTIF</h6>
                        <h2 class="fw-bold"><?= $pinjam_aktif; ?></h2>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="row align-items-start">
            <?php if ($level == 'admin') : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm p-4">
                        <h5 class="fw-bold mb-3" style="color: #1e0e60;"><i class="bi bi-plus-circle"></i> Pinjam Buku</h5>
                        <form action="proses_pinjam.php" method="POST">

                            <div class="mb-3">
                                <label class="form-label small fw-bold">USERNAME SISWA</label>
                                <input type="text" id="input_username" class="form-control border-0 bg-light" placeholder="Ketik username..." required autocomplete="off">
                                <small id="user_status" class="status-msg"></small>
                                <input type="hidden" name="id_anggota" id="id_anggota_hidden">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">NAMA SISWA</label>
                                <input type="text" id="display_nama" class="form-control border-0 input-readonly" placeholder="Nama muncul otomatis..." readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">JUDUL BUKU</label>
                                <select name="id_buku" class="form-select bg-light border-0" required>
                                    <option value="">-- Pilih Buku --</option>
                                    <?php
                                    $bku = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");
                                    while ($b = mysqli_fetch_array($bku)) echo "<option value='$b[id_buku]'>$b[judul] (Stok: $b[stok])</option>";
                                    ?>
                                </select>
                            </div>

                            <button type="submit" id="btn_submit" class="btn btn-custom w-100 py-2 shadow" disabled>SIMPAN PINJAMAN</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="<?= ($level == 'admin') ? 'col-md-8' : 'col-md-12'; ?>">
                <div class="card shadow-sm p-4">
                    <div class="mb-4">
                        <h5 class="fw-bold" style="color: #1e0e60;"><i class="bi bi-collection"></i> Katalog Koleksi</h5>
                        <form method="GET" class="row g-2 mt-2">
                            <div class="col-md-5"><input type="text" name="cari" class="form-control border-0 bg-light" placeholder="Cari judul..." value="<?= htmlspecialchars($keyword); ?>"></div>
                            <div class="col-md-4">
                                <select name="genre" class="form-select border-0 bg-light">
                                    <option value="">Semua Genre</option>
                                    <optgroup label="Akademik">
                                        <option value="Informatika" <?= $genre_filter == 'Informatika' ? 'selected' : ''; ?>>Informatika</option>
                                        <option value="Sains" <?= $genre_filter == 'Sains' ? 'selected' : ''; ?>>Sains</option>
                                        <option value="Bahasa" <?= $genre_filter == 'Bahasa' ? 'selected' : ''; ?>>Bahasa</option>
                                    </optgroup>
                                    <optgroup label="Fiksi">
                                        <option value="Novel" <?= $genre_filter == 'Novel' ? 'selected' : ''; ?>>Novel</option>
                                        <option value="Action" <?= $genre_filter == 'Action' ? 'selected' : ''; ?>>Action</option>
                                        <option value="Slice of Life" <?= $genre_filter == 'Slice of Life' ? 'selected' : ''; ?>>Slice of Life</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-3"><button type="submit" class="btn btn-custom w-100">Cari</button></div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="text-center" style="background-color: #1e0e60; color: white;">
                                <tr>
                                    <th>INFORMASI BUKU</th>
                                    <th>GENRE</th>
                                    <th>STOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($res)) :
                                    $badge = ($row['stok'] > 0) ? 'background-color: #e9b321; color: #1e0e60;' : 'background-color: #743454; color: white;';
                                ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= $row['judul']; ?></div><small class="text-muted"><?= $row['pengarang']; ?> (<?= $row['tahun_terbit']; ?>)</small>
                                        </td>
                                        <td class="text-center"><span class="badge rounded-pill badge-genre"><?= $row['genre']; ?></span></td>
                                        <td class="text-center"><span class="badge rounded-pill px-3 py-2" style="<?= $badge ?>"><?= $row['stok']; ?> Unit</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('input_username').addEventListener('input', function() {
            let username = this.value;
            let statusText = document.getElementById('user_status');
            let displayNama = document.getElementById('display_nama');
            let btnSubmit = document.getElementById('btn_submit');
            let hiddenId = document.getElementById('id_anggota_hidden');

            if (username.length > 2) {
                fetch('get_siswa_by_username.php?username=' + username)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            displayNama.value = data.nama;
                            hiddenId.value = data.id_anggota;
                            statusText.innerText = "Siswa ditemukan ✓";
                            statusText.style.color = "green";
                            btnSubmit.disabled = false;
                        } else {
                            displayNama.value = "";
                            statusText.innerText = "Username tidak terdaftar!";
                            statusText.style.color = "red";
                            btnSubmit.disabled = true;
                        }
                    });
            } else {
                displayNama.value = "";
                statusText.innerText = "";
                btnSubmit.disabled = true;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>