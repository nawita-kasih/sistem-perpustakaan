<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_pinjam = $_GET['id'];
$id_buku   = $_GET['id_buku'];
$denda     = isset($_GET['denda']) ? $_GET['denda'] : 0;
$tgl_kembali = date('Y-m-d'); // Tanggal hari ini

// 1. Update status, denda, dan TANGGAL KEMBALI
$sql = "UPDATE peminjaman SET 
        status = 'kembali', 
        tgl_kembali = '$tgl_kembali', 
        denda = '$denda' 
        WHERE id_pinjam = '$id_pinjam'";

if (mysqli_query($conn, $sql)) {
    // 2. Tambah stok buku kembali
    mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

    header("location:data_pinjam.php?pesan=berhasil_kembali");
} else {
    echo "Error: " . mysqli_error($conn);
}
