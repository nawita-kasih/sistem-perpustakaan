<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php?pesan=belum_login");
    exit;
}

// Cek apakah level sudah ada di session untuk mencegah error "Undefined array key"
$level = isset($_SESSION['level']) ? $_SESSION['level'] : '';

include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .welcome-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Tambahan agar scroll halus saat diklik */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <div class="welcome-box">
            <h4 class="fw-bold">Selamat Datang, <?= $_SESSION['nama']; ?>!</h4>
            <p class="text-muted">Status Login: <span class="badge bg-info text-dark"><?= strtoupper($level); ?></span></p>
        </div>

        <div class="row">
            <?php if ($level == 'admin') : ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-dark text-white">Input Peminjaman</div>
                        <div class="card-body">
                            <form action="proses_pinjam.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Anggota:</label>
                                    <select name="id_anggota" class="form-select" required>
                                        <option value=""> Pilih Siswa </option>
                                        <?php
                                        $agt = mysqli_query($conn, "SELECT * FROM anggota");
                                        while ($d = mysqli_fetch_array($agt)) echo "<option value='$d[id_anggota]'>$d[nama]</option>";
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pilih Buku:</label>
                                    <select name="id_buku" class="form-select" required>
                                        <option value=""> Pilih Judul Buku </option>
                                        <?php
                                        $bku = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
                                        while ($b = mysqli_fetch_array($bku)) echo "<option value='$b[id_buku]'>$b[judul] (Stok: $b[stok])</option>";
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-dark w-100">Proses Pinjam Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="daftar-buku" class="<?= ($level == 'admin') ? 'col-md-8' : 'col-md-12'; ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">Koleksi Buku Tersedia</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Judul Buku</th>
                                    <th class="text-center">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM buku");
                                if (mysqli_num_rows($res) == 0) {
                                    echo "<tr><td colspan='2' class='text-center py-4 text-muted'>Data buku tersedia.</td></tr>";
                                }
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $badge = ($row['stok'] > 0) ? 'bg-success' : 'bg-danger';
                                    echo "<tr>
                                            <td>{$row['judul']}</td>
                                            <td class='text-center'><span class='badge $badge'>{$row['stok']}</span></td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>