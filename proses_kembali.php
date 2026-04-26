<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_pinjam = $_GET['id'];
$id_buku   = $_GET['id_buku'];
$tgl_sekarang = date('Y-m-d');

// 1. Ambil data peminjaman & pengaturan untuk hitung denda dinamis
$sql_cek = "SELECT p.*, s.denda_per_hari as denda_default, s.batas_hari_pinjam 
            FROM peminjaman p 
            CROSS JOIN pengaturan s 
            WHERE p.id_pinjam = '$id_pinjam' AND s.id = 1";

$query_cek = mysqli_query($conn, $sql_cek);
$data = mysqli_fetch_assoc($query_cek);

// Tentukan deadline (Berdasarkan tgl_pinjam + batas_hari)
$tgl_pinjam = $data['tgl_pinjam'];
$batas = ($data['batas_hari'] > 0) ? $data['batas_hari'] : $data['batas_hari_pinjam'];
$tgl_deadline = date('Y-m-d', strtotime($tgl_pinjam . " +$batas days"));

// 2. Hitung Denda secara Dinamis
$denda = 0;
$selisih = 0;
if (strtotime($tgl_sekarang) > strtotime($tgl_deadline)) {
    $detik = strtotime($tgl_sekarang) - strtotime($tgl_deadline);
    $selisih = floor($detik / (60 * 60 * 24));

    // Ambil nominal denda dari transaksi atau dari pengaturan admin
    $nominal_harian = ($data['denda_per_hari'] > 0) ? $data['denda_per_hari'] : $data['denda_default'];
    $denda = $selisih * $nominal_harian;
}

// 3. Update status peminjaman (PASTIKAN nama kolom tgl_kembali sesuai dengan laporan)
$update_pinjam = mysqli_query($conn, "UPDATE peminjaman SET 
    tgl_kembali = '$tgl_sekarang', 
    denda = '$denda', 
    status = 'kembali' 
    WHERE id_pinjam = '$id_pinjam'");

// 4. Tambahkan kembali stok buku
$update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

if ($update_pinjam && $update_stok) {
    if ($denda > 0) {
        $denda_f = number_format($denda, 0, ',', '.');
        echo "<script>alert('Buku dikembalikan. Terlambat $selisih hari, denda: Rp $denda_f'); window.location='data_pinjam.php';</script>";
    } else {
        echo "<script>alert('Buku dikembalikan tepat waktu.'); window.location='data_pinjam.php';</script>";
    }
} else {
    echo "Gagal memproses: " . mysqli_error($conn);
}
