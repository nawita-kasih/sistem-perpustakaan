<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = md5($_POST['password']);

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($query);
    $_SESSION['username'] = $username;
    $_SESSION['nama']     = $data['nama_lengkap'];
    $_SESSION['level']    = $data['level'];
    $_SESSION['status']   = "login";
    header("location:index.php");
} else {
    // Tampilan SweetAlert Gagal Login
    echo "<!DOCTYPE html><html><head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap' rel='stylesheet'><style>body{font-family:'Poppins', sans-serif; background: #f0edf8;}</style></head><body>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: 'Username atau Password salah.',
            confirmButtonColor: '#743454',
            confirmButtonText: 'Coba Lagi'
        }).then((result) => {
            window.location='login.php';
        });
    </script></body></html>";
}
