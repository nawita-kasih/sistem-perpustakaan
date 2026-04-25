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
    $no_telp_baru = mysqli_real_escape_string($conn, $_POST['no_telp']);
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f0edf8;
            /* Dull Lavender muda */
            font-family: 'Poppins', sans-serif;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-top: 50px;
            border: none;
        }

        /* Ikon Profil Bulat */
        .profile-avatar {
            width: 100px;
            height: 100px;
            background-color: #b1a1e5;
            color: #1e0e60;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto 20px;
            border-radius: 50%;
            border: 4px solid #f0edf8;
        }

        /* Tombol Update - Fuel Yellow */
        .btn-update {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-update:hover {
            background-color: #b1a1e5;
            transform: translateY(-2px);
        }

        /* Tombol Kembali - Violent Violet / Outline */
        .btn-back {
            color: #1e0e60;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-back:hover {
            color: #743454;
            /* Cosmic */
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #b1a1e5;
        }

        .label-custom {
            color: #1e0e60;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="profile-card shadow-lg">
                    <div class="text-center">
                        <div class="profile-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h3 class="fw-bold mb-0" style="color: #1e0e60;">Informasi Akun</h3>
                        <p class="text-muted small mb-4">Perbarui data kontak dan keamanan Anda</p>
                    </div>
                    <hr style="border-top: 2px solid #f0edf8;">

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label label-custom fw-bold text-uppercase">Nama Lengkap</label>
                            <input type="text" class="form-control py-2" value="<?= $data['nama_lengkap']; ?>" readonly style="cursor: not-allowed; opacity: 0.7;">
                            <div class="form-text small" style="color: #743454;">* Hubungi Admin jika ingin mengubah nama</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label label-custom fw-bold text-uppercase">Username</label>
                            <input type="text" class="form-control py-2" value="<?= $data['username']; ?>" readonly style="cursor: not-allowed; opacity: 0.7;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label label-custom fw-bold text-uppercase">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light"><i class="bi bi-whatsapp" style="color: #25D366;"></i></span>
                                <input type="text" name="no_telp" class="form-control py-2 border-0 bg-light" value="<?= $data['no_telp']; ?>" required placeholder="Contoh: 62812345678">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label label-custom fw-bold text-uppercase">Ganti Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-0 bg-light"><i class="bi bi-key" style="color: #1e0e60;"></i></span>
                                <input type="password" name="pass_baru" class="form-control py-2 border-0 bg-light" placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" name="update" class="btn btn-update py-2 rounded-pill shadow-sm">
                                <i class="bi bi-check2-circle"></i> SIMPAN PERUBAHAN
                            </button>
                            <a href="riwayat_pribadi.php" class="btn btn-back text-center mt-2 small">
                                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>