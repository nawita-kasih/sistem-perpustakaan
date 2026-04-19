<?php
include 'koneksi.php';

$id_pinjam = $_GET['id'];
$id_buku   = $_GET['id_buku'];
$tgl_sekarang = date('Y-m-d');

// 1. Ambil data peminjaman untuk cek deadline
$query_cek = mysqli_query($conn, "SELECT tgl_kembali_seharusnya FROM peminjaman WHERE id_pinjam = '$id_pinjam'");
$data = mysqli_fetch_assoc($query_cek);
$tgl_deadline = $data['tgl_kembali_seharusnya'];

// 2. Hitung Denda
$denda = 0;
if (strtotime($tgl_sekarang) > strtotime($tgl_deadline)) {
    $selisih = (strtotime($tgl_sekarang) - strtotime($tgl_deadline)) / (60 * 60 * 24);
    $denda = $selisih * 1000; // Denda Rp 1.000 per hari
}

// 3. Update status peminjaman, tgl_dikembalikan, dan denda
$update_pinjam = mysqli_query($conn, "UPDATE peminjaman SET 
    tgl_dikembalikan = '$tgl_sekarang', 
    denda = '$denda', 
    status = 'kembali' 
    WHERE id_pinjam = '$id_pinjam'");

// 4. Tambahkan kembali stok buku
$update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

if ($update_pinjam && $update_stok) {
    if ($denda > 0) {
        echo "<script>alert('Buku dikembalikan. Siswa terlambat $selisih hari, denda: Rp $denda'); window.location='data_pinjam.php';</script>";
    } else {
        echo "<script>alert('Buku dikembalikan tepat waktu.'); window.location='data_pinjam.php';</script>";
    }
}
