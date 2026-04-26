<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan_sekolah"; // <--- Pastikan nama database kamu benar di sini

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
