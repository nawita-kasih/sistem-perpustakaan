<?php
include 'koneksi.php';

/**
 * PROSES TAMBAH BUKU
 * Dijalankan saat tombol Simpan di buku.php diklik
 */
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {

    // Tangkap data dan amankan dari SQL Injection
    $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
    $genre        = mysqli_real_escape_string($conn, $_POST['genre']); // Ini kunci agar filter jalan
    $pengarang    = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $penerbit     = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $stok         = mysqli_real_escape_string($conn, $_POST['stok']);

    // Query SQL - Pastikan urutan dan nama kolom sesuai dengan database
    $sql = "INSERT INTO buku (judul, genre, pengarang, penerbit, tahun_terbit, stok) 
            VALUES ('$judul', '$genre', '$pengarang', '$penerbit', '$tahun_terbit', '$stok')";

    if (mysqli_query($conn, $sql)) {
        // Berhasil simpan, kembalikan ke buku.php
        header("Location: buku.php?pesan=berhasil_tambah");
        exit;
    } else {
        // Gagal simpan, tampilkan pesan error sistem
        echo "<script>
                alert('Gagal menambah buku! Error: " . mysqli_error($conn) . "');
                window.location='buku.php';
              </script>";
    }
}

/**
 * PROSES HAPUS BUKU
 * Dijalankan saat tombol tong sampah diklik
 */
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM buku WHERE id_buku = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: buku.php?pesan=berhasil_hapus");
        exit;
    } else {
        echo "<script>
                alert('Gagal menghapus! Mungkin buku ini sedang dipinjam.');
                window.location='buku.php';
              </script>";
    }
}

/**
 * JIKA DIAKSES LANGSUNG TANPA AKSI
 */
header("Location: buku.php");
exit;
