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
    echo "<script>alert('Login Gagal! Username atau Password salah.'); window.location='login.php';</script>";
}
