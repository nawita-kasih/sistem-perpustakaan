<?php
include 'koneksi.php';

// Tangkap data
$id_anggota = mysqli_real_escape_string($conn, $_POST['id_anggota']);
$id_buku    = mysqli_real_escape_string($conn, $_POST['id_buku']);
$tgl_pinjam = date('Y-m-d');

// Logika: Batas kembali adalah 7 hari dari tanggal pinjam
$tgl_kembali_seharusnya = date('Y-m-d', strtotime($tgl_pinjam . ' + 7 days'));

// 1. Cek stok buku terakhir
$cek_stok = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku = '$id_buku'");
$data = mysqli_fetch_assoc($cek_stok);

if ($data['stok'] > 0) {
    // 2. Input ke tabel peminjaman
    $sql_pinjam = "INSERT INTO peminjaman (id_buku, id_anggota, tgl_pinjam, tgl_kembali_seharusnya, denda, status) 
                   VALUES ('$id_buku', '$id_anggota', '$tgl_pinjam', '$tgl_kembali_seharusnya', 0, 'pinjam')";

    $query_pinjam = mysqli_query($conn, $sql_pinjam);

    // 3. Kurangi stok buku
    $query_update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = '$id_buku'");

    if ($query_pinjam && $query_update_stok) {
        // Gunakan format tanggal yang lebih enak dibaca di alert
        $tgl_indo = date('d-m-Y', strtotime($tgl_kembali_seharusnya));
        echo "<script>alert('Peminjaman Berhasil! Wajib dikembalikan paling lambat tanggal: $tgl_indo'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan pada sistem!'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('Maaf, stok buku ini sedang habis!'); window.location='index.php';</script>";
}
