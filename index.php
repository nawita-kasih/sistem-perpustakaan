<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}
$level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
$nama_user = $_SESSION['nama'];
include 'koneksi.php';

$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

if ($level == 'admin') {
    $total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
    $total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
    $pinjam_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status='pinjam'"))['total'];
} else {
    $pinjam_saya = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman 
                   JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota 
                   WHERE anggota.nama = '$nama_user' AND status='pinjam'"))['total'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
        }

        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <div class="welcome-section shadow-lg">
            <h2 class="fw-bold">Halo, <?= $nama_user; ?>! 👋</h2>
            <p class="lead mb-0">Kelola aktivitas perpustakaan Anda dengan mudah dan cepat.</p>
        </div>

        <div class="row mb-4 text-white">
            <?php if ($level == 'admin') : ?>
                <div class="col-md-4">
                    <div class="card p-3 mb-3 shadow-sm bg-primary border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small">TOTAL BUKU</h6>
                                <h2 class="fw-bold"><?= $total_buku; ?></h2>
                            </div><i class="bi bi-book stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-3 shadow-sm bg-success border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small">ANGGOTA</h6>
                                <h2 class="fw-bold"><?= $total_siswa; ?></h2>
                            </div><i class="bi bi-people stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-3 shadow-sm bg-warning border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small">PINJAM AKTIF</h6>
                                <h2 class="fw-bold"><?= $pinjam_aktif; ?></h2>
                            </div><i class="bi bi-clock-history stat-icon"></i>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-md-12">
                    <div class="card p-4 shadow-sm bg-info border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small">BUKU YANG ANDA PINJAM</h6>
                                <h2 class="fw-bold"><?= $pinjam_saya; ?> Buku</h2>
                            </div><i class="bi bi-journal-text stat-icon"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php if ($level == 'admin') : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm p-4 h-100">
                        <h5 class="fw-bold mb-4 text-center">Pinjam Buku Baru</h5>
                        <form action="proses_pinjam.php" method="POST">
                            <div class="mb-3"><label class="form-label small fw-bold">PILIH SISWA</label><select name="id_anggota" class="form-select border-0 bg-light py-2" required>
                                    <option value="">-- Cari Siswa --</option>
                                    <?php $agt = mysqli_query($conn, "SELECT * FROM anggota ORDER BY nama ASC");
                                    while ($d = mysqli_fetch_array($agt)) echo "<option value='$d[id_anggota]'>$d[nama]</option>"; ?>
                                </select></div>
                            <div class="mb-4"><label class="form-label small fw-bold">PILIH BUKU</label><select name="id_buku" class="form-select border-0 bg-light py-2" required>
                                    <option value="">-- Cari Judul --</option>
                                    <?php $bku = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");
                                    while ($b = mysqli_fetch_array($bku)) echo "<option value='$b[id_buku]'>$b[judul] (Stok: $b[stok])</option>"; ?>
                                </select></div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow">PROSES PINJAM</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="<?= ($level == 'admin') ? 'col-md-8' : 'col-md-12'; ?>">
                <div class="card shadow-sm p-4">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-0">Katalog Koleksi</h5>
                        </div>
                        <div class="col-md-6 mt-2 mt-md-0">
                            <form action="" method="GET" class="d-flex"><input type="text" name="cari" class="form-control form-control-sm me-2 border-0 bg-light" placeholder="Cari judul..." value="<?= htmlspecialchars($keyword); ?>"><button type="submit" class="btn btn-dark btn-sm"><i class="bi bi-search"></i></button></form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>JUDUL BUKU</th>
                                    <th>STOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = $keyword ? "SELECT * FROM buku WHERE judul LIKE '%$keyword%' ORDER BY judul ASC" : "SELECT * FROM buku ORDER BY judul ASC";
                                $res = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($res) == 0) echo "<tr><td colspan='2' class='text-center py-4'>Data tidak ditemukan.</td></tr>";
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $badge = ($row['stok'] > 0) ? 'bg-success' : 'bg-danger';
                                    echo "<tr><td>{$row['judul']}</td><td class='text-center'><span class='badge rounded-pill $badge'>{$row['stok']} Unit</span></td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>