<?php
include 'koneksi.php';
$username = mysqli_real_escape_string($conn, $_GET['username']);
$id_anggota = isset($_GET['id_anggota']) ? $_GET['id_anggota'] : '';

// Jika sedang EDIT, jangan salahkan jika username sama dengan miliknya sendiri
$sql = "SELECT * FROM users WHERE username = '$username'";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    echo "ambil"; // Username sudah dipakai
} else {
    echo "tersedia";
}
