<?php
include 'koneksi.php';

$id_anggota = $_POST['id_anggota'];
$id_buku    = $_POST['id_buku'];
$tgl_pinjam = date('Y-m-d');

// Cek stok buku terakhir
$cek_stok = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
$data = mysqli_fetch_assoc($cek_stok);

if ($data['stok'] > 0) {
    // Input ke tabel peminjaman
    $query_pinjam = mysqli_query($conn, "INSERT INTO peminjaman (id_buku, id_anggota, tgl_pinjam, status) 
                                         VALUES ('$id_buku', '$id_anggota', '$tgl_pinjam', 'pinjam')");

    // Kurangi stok buku
    $query_update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");

    if ($query_pinjam && $query_update_stok) {
        echo "<script>alert('Buku berhasil dipinjam!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Maaf, stok habis!'); window.location='index.php';</script>";
}
