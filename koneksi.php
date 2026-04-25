<?php
date_default_timezone_set('Asia/Jakarta');

$conn = mysqli_connect("localhost", "root", "", "perpustakaan_sekolah");
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan_sekolah";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
