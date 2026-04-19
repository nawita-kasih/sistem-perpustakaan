<?php
session_start();
include 'koneksi.php';

// Proteksi level siswa
if (!isset($_SESSION['level']) || $_SESSION['level'] != "siswa") {
    header("location:index.php");
    exit;
}

$username_session = $_SESSION['username'];

// Ambil data lengkap siswa dari database
$query = mysqli_query($conn, "SELECT users.*, anggota.no_telp, anggota.id_anggota 
                              FROM users 
                              JOIN anggota ON users.nama_lengkap = anggota.nama 
                              WHERE users.username = '$username_session'");
$data = mysqli_fetch_assoc($query);

// Proses Update Data (Password atau No Telp)
if (isset($_POST['update'])) {
    $no_telp_baru = $_POST['no_telp'];
    $pass_baru    = $_POST['pass_baru'];
    $id_agt       = $data['id_anggota'];

    // Update Nomor Telepon di tabel anggota
    mysqli_query($conn, "UPDATE anggota SET no_telp = '$no_telp_baru' WHERE id_anggota = '$id_agt'");

    // Update Password jika diisi
    if (!empty($pass_baru)) {
        $pass_hash = md5($pass_baru);
        mysqli_query($conn, "UPDATE users SET password = '$pass_hash' WHERE username = '$username_session'");
    }

    echo "<script>alert('Data akun berhasil diperbarui!'); window.location='profil.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Saya - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="profile-card shadow-sm">
                    <h3 class="fw-bold mb-0">Informasi Akun</h3>
                    <p class="text-muted small mb-4">Kelola informasi profil dan keamanan akun Anda.</p>
                    <hr>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NAMA LENGKAP</label>
                            <input type="text" class="form-control bg-light" value="<?= $data['nama_lengkap']; ?>" readonly>
                            <div class="form-text">Nama hanya dapat diubah oleh Admin.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">USERNAME</label>
                            <input type="text" class="form-control bg-light" value="<?= $data['username']; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">NOMOR WHATSAPP</label>
                            <input type="text" name="no_telp" class="form-control" value="<?= $data['no_telp']; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">GANTI PASSWORD</label>
                            <input type="password" name="pass_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="update" class="btn btn-dark">Simpan Perubahan</button>
                            <a href="riwayat_pribadi.php" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>