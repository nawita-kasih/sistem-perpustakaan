<?php
include 'koneksi.php';

$username = isset($_GET['username']) ? mysqli_real_escape_string($conn, $_GET['username']) : '';

// Cari data anggota berdasarkan username di tabel users
$query = mysqli_query($conn, "SELECT anggota.id_anggota, anggota.nama 
                               FROM anggota 
                               JOIN users ON anggota.nama = users.nama_lengkap 
                               WHERE users.username = '$username' LIMIT 1");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    echo json_encode([
        'status' => 'success',
        'nama' => $row['nama'],
        'id_anggota' => $row['id_anggota']
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
