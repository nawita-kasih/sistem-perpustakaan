<?php
include 'koneksi.php';

$id_anggota = $_POST['id_anggota'];
$id_buku    = $_POST['id_buku'];
$tgl_pinjam = date('Y-m-d');

// Logika: Batas kembali adalah 7 hari dari tanggal pinjam
$tgl_kembali_seharusnya = date('Y-m-d', strtotime($tgl_pinjam . ' + 7 days'));

// Cek stok buku terakhir
$cek_stok = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
$data = mysqli_fetch_assoc($cek_stok);

if ($data['stok'] > 0) {
    // Input ke tabel peminjaman (Ditambah kolom tgl_kembali_seharusnya dan denda default 0)
    $sql_pinjam = "INSERT INTO peminjaman (id_buku, id_anggota, tgl_pinjam, tgl_kembali_seharusnya, denda, status) 
                   VALUES ('$id_buku', '$id_anggota', '$tgl_pinjam', '$tgl_kembali_seharusnya', 0, 'pinjam')";

    $query_pinjam = mysqli_query($conn, $sql_pinjam);

    // Kurangi stok buku
    $query_update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");

    if ($query_pinjam && $query_update_stok) {
        echo "<script>alert('Buku berhasil dipinjam! Batas kembali: $tgl_kembali_seharusnya'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memproses data!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Maaf, stok habis!'); window.location='index.php';</script>";
}
