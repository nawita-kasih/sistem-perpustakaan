<?php
include 'koneksi.php';

// Menangkap ID dari URL
$id_pinjam = $_GET['id'];
$id_buku   = $_GET['id_buku'];

// Update status peminjaman menjadi 'kembali'
$update_status = mysqli_query($conn, "UPDATE peminjaman SET status = 'kembali' WHERE id_pinjam = '$id_pinjam'");

// Tambahkan kembali stok buku (+1)
$tambah_stok = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

if ($update_status && $tambah_stok) {
    echo "<script>alert('Buku telah dikembalikan!'); window.location='data_pinjam.php';</script>";
} else {
    echo "Gagal memproses pengembalian.";
}
