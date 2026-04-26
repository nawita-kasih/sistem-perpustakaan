<?php
include 'koneksi.php';

$username = isset($_GET['username']) ? mysqli_real_escape_string($conn, $_GET['username']) : '';

// Ambil id, nama, dan kelas dengan JOIN tabel users dan anggota
$query = mysqli_query($conn, "SELECT anggota.id_anggota, anggota.nama, anggota.kelas 
                               FROM anggota 
                               JOIN users ON anggota.nama = users.nama_lengkap 
                               WHERE users.username = '$username' LIMIT 1");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    echo json_encode([
        'status' => 'success',
        'nama' => $row['nama'],
        'kelas' => $row['kelas'], // Menambahkan data kelas
        'id_anggota' => $row['id_anggota']
    ]);
} else {
    echo json_encode(['status' => 'error']);
}
