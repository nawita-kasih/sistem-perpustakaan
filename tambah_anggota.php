<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != "admin") {
    header("location:index.php");
    exit;
}
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_telp  = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    // 1. CEK DAHULU: Apakah username sudah dipakai?
    $cek_user = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        // Jika username sudah ada, tampilkan alert dan hentikan proses
        echo "<script>alert('Gagal! Username sudah digunakan oleh orang lain.'); window.history.back();</script>";
    } else {
        // 2. Jika aman, lakukan simpan ke tabel anggota
        $query_agt = mysqli_query($conn, "INSERT INTO anggota (nama, no_telp) VALUES ('$nama', '$no_telp')");

        // 3. Simpan ke tabel users
        $query_usr = mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, level) 
                                          VALUES ('$username', '$password', '$nama', 'siswa')");

        if ($query_agt && $query_usr) {
            echo "<script>alert('Siswa berhasil didaftarkan!'); window.location='tambah_anggota.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan pada server.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Siswa - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-custom {
            background: white;
            border-radius: 15px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom shadow-sm p-4">
                    <h3 class="fw-bold mb-3">Registrasi Anggota Baru</h3>
                    <p class="text-muted small">Input data siswa untuk akses layanan perpustakaan.</p>
                    <hr>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap Siswa</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp (628...)</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="628xxxxxxxx" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username Login</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password Default</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-dark w-100">Daftarkan Siswa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>