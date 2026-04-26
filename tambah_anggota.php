<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != "admin") {
    header("location:index.php");
    exit;
}
include 'koneksi.php';

// --- LOGIKA 1: PROSES SIMPAN (TAMBAH ATAU EDIT) ---
if (isset($_POST['submit'])) {
    $id_anggota = $_POST['id_anggota'];
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_telp    = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $kelas      = mysqli_real_escape_string($conn, $_POST['kelas']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $password   = $_POST['password'];

    if (empty($id_anggota)) {
        // MODE: TAMBAH BARU
        // Cek kembali di server untuk keamanan ganda
        $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Gagal! Username sudah digunakan orang lain.'); window.history.back();</script>";
            exit;
        }

        $pass_md5 = md5($password);
        mysqli_query($conn, "INSERT INTO anggota (nama, no_telp, kelas) VALUES ('$nama', '$no_telp', '$kelas')");
        mysqli_query($conn, "INSERT INTO users (username, password, nama_lengkap, level) VALUES ('$username', '$pass_md5', '$nama', 'siswa')");
        echo "<script>alert('Siswa berhasil didaftarkan!'); window.location='tambah_anggota.php';</script>";
    } else {
        // MODE: EDIT DATA
        $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM anggota WHERE id_anggota = '$id_anggota'"));
        $nama_lama = $old_data['nama'];

        mysqli_query($conn, "UPDATE anggota SET nama='$nama', no_telp='$no_telp', kelas='$kelas' WHERE id_anggota='$id_anggota'");

        if (!empty($password)) {
            $pass_md5 = md5($password);
            mysqli_query($conn, "UPDATE users SET username='$username', password='$pass_md5', nama_lengkap='$nama' WHERE nama_lengkap='$nama_lama'");
        } else {
            mysqli_query($conn, "UPDATE users SET username='$username', nama_lengkap='$nama' WHERE nama_lengkap='$nama_lama'");
        }
        echo "<script>alert('Data siswa berhasil diperbarui!'); window.location='tambah_anggota.php';</script>";
    }
}

// --- LOGIKA 2: PROSES HAPUS ---
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $cek_agt = mysqli_query($conn, "SELECT nama FROM anggota WHERE id_anggota = '$id'");

    if (mysqli_num_rows($cek_agt) > 0) {
        $data_agt = mysqli_fetch_assoc($cek_agt);
        $nama_agt = $data_agt['nama'];
        try {
            mysqli_query($conn, "DELETE FROM users WHERE nama_lengkap = '$nama_agt'");
            mysqli_query($conn, "DELETE FROM anggota WHERE id_anggota = '$id'");
            echo "<script>alert('Data siswa berhasil dihapus.'); window.location='tambah_anggota.php';</script>";
        } catch (mysqli_sql_exception $e) {
            echo "<script>alert('Gagal menghapus! Siswa ini masih memiliki riwayat peminjaman buku.'); window.location='tambah_anggota.php';</script>";
        }
    }
}

// --- LOGIKA 3: AMBIL DATA UNTUK EDIT ---
$edit_data = ['id_anggota' => '', 'nama' => '', 'no_telp' => '', 'kelas' => '', 'username' => ''];
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT anggota.*, users.username FROM anggota JOIN users ON anggota.nama = users.nama_lengkap WHERE id_anggota = '$id_edit'");
    if (mysqli_num_rows($q_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($q_edit);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Siswa - E-Perpus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0edf8;
        }

        .card-custom {
            background: white;
            border-radius: 20px;
            border: none;
        }

        .form-label {
            color: #1e0e60;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .btn-fuel {
            background-color: #e9b321;
            color: #1e0e60;
            border: none;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-fuel:hover:not(:disabled) {
            background-color: #b1a1e5;
            color: #1e0e60;
            transform: translateY(-2px);
        }

        .btn-fuel:disabled {
            background-color: #d1c8f0;
            color: #8e84ad;
            cursor: not-allowed;
        }

        .table-custom-header {
            background-color: #1e0e60;
            color: white;
        }

        .text-violet {
            color: #1e0e60;
        }

        .form-control,
        .form-select {
            background-color: #f8f9fa;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
        }

        #msg_username {
            font-size: 0.75rem;
            margin-top: 5px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container-fluid px-4 mt-4">
        <div class="row align-items-start">
            <div class="col-md-4 mb-4">
                <div class="card card-custom shadow-lg p-4">
                    <div class="text-center mb-4">
                        <div class="fs-1 text-violet">
                            <i class="bi <?= isset($_GET['edit']) ? 'bi-pencil-square' : 'bi-person-plus-fill'; ?>"></i>
                        </div>
                        <h4 class="fw-bold text-violet mb-1"><?= isset($_GET['edit']) ? 'Edit Data Siswa' : 'Registrasi Siswa'; ?></h4>
                        <p class="text-muted small">Kelola data akses anggota</p>
                    </div>
                    <hr style="border-top: 2px solid #f0edf8;">

                    <form action="" method="POST">
                        <input type="hidden" name="id_anggota" id="id_anggota_val" value="<?= $edit_data['id_anggota']; ?>">

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Username</label>
                            <input type="text" name="username" id="input_username" class="form-control border-0 py-2" value="<?= $edit_data['username']; ?>" placeholder="Username login..." required autocomplete="off">
                            <small id="msg_username"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control border-0 py-2" value="<?= $edit_data['nama']; ?>" placeholder="Nama siswa..." required>
                        </div>

                        <div class="row">
                            <div class="col-md-7 mb-3">
                                <label class="form-label text-uppercase">WhatsApp</label>
                                <input type="text" name="no_telp" class="form-control border-0 py-2" value="<?= $edit_data['no_telp']; ?>" placeholder="628..." required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label text-uppercase">Kelas</label>
                                <select name="kelas" class="form-select border-0 py-2" required>
                                    <option value="" disabled selected>Pilih...</option>
                                    <option value="IPA" <?= ($edit_data['kelas'] == 'IPA') ? 'selected' : ''; ?>>IPA</option>
                                    <option value="IPS" <?= ($edit_data['kelas'] == 'IPS') ? 'selected' : ''; ?>>IPS</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase">Password <?= isset($_GET['edit']) ? '(Kosongkan jika tetap)' : ''; ?></label>
                            <input type="password" name="password" class="form-control border-0 py-2" placeholder="Password..." <?= isset($_GET['edit']) ? '' : 'required'; ?>>
                        </div>

                        <button type="submit" name="submit" id="btn_submit" class="btn btn-fuel w-100 py-3 rounded-pill shadow-sm text-uppercase">
                            <?= isset($_GET['edit']) ? 'Simpan Perubahan' : 'Daftarkan Siswa'; ?>
                        </button>
                        <?php if (isset($_GET['edit'])): ?>
                            <a href="tambah_anggota.php" class="btn btn-light w-100 rounded-pill mt-2 small">Batal Edit</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-custom shadow-lg p-4">
                    <h4 class="fw-bold text-violet mb-4"><i class="bi bi-people-fill me-2"></i>Daftar Seluruh Siswa</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-custom-header text-center small text-uppercase">
                                <tr>
                                    <th class="py-3">No</th>
                                    <th class="py-3">Nama</th>
                                    <th class="py-3">Kelas</th>
                                    <th class="py-3">WhatsApp</th>
                                    <th class="py-3">Username</th>
                                    <th class="py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query_list = mysqli_query($conn, "SELECT anggota.*, users.username FROM anggota LEFT JOIN users ON anggota.nama = users.nama_lengkap ORDER BY anggota.nama ASC");
                                while ($row = mysqli_fetch_array($query_list)) { ?>
                                    <tr>
                                        <td class="text-center small text-muted"><?= $no++; ?></td>
                                        <td class="fw-bold text-violet"><?= $row['nama']; ?></td>
                                        <td class="text-center"><span class="badge bg-light text-violet border"><?= $row['kelas']; ?></span></td>
                                        <td class="text-center">
                                            <a href="https://wa.me/<?= $row['no_telp']; ?>" target="_blank" class="text-success text-decoration-none small">
                                                <i class="bi bi-whatsapp me-1"></i><?= $row['no_telp']; ?>
                                            </a>
                                        </td>
                                        <td class="text-center small"><?= $row['username']; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="tambah_anggota.php?edit=<?= $row['id_anggota']; ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="bi bi-pencil"></i></a>
                                                <a href="tambah_anggota.php?hapus=<?= $row['id_anggota']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Hapus siswa ini?')"><i class="bi bi-trash"></i></a>
                                            </div>
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

    <script>
        const inputUser = document.getElementById('input_username');
        const msgUser = document.getElementById('msg_username');
        const btnSubmit = document.getElementById('btn_submit');
        const idAnggota = document.getElementById('id_anggota_val').value;

        inputUser.addEventListener('input', function() {
            let username = this.value;

            if (username.length > 2) {
                fetch('cek_username.php?username=' + username + '&id_anggota=' + idAnggota)
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'ambil') {
                            msgUser.innerText = 'Username sudah terdaftar!';
                            msgUser.style.color = '#743454';
                            // Hanya matikan tombol jika mode TAMBAH BARU
                            if (idAnggota === "") {
                                btnSubmit.disabled = true;
                            }
                        } else {
                            msgUser.innerText = 'Username tersedia ✓';
                            msgUser.style.color = 'green';
                            btnSubmit.disabled = false;
                        }
                    });
            } else {
                msgUser.innerText = '';
                if (idAnggota === "") btnSubmit.disabled = true;
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>