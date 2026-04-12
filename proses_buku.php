<?php
include 'koneksi.php';

// Cek apakah ada aksi Tambah melalui POST
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $judul = $_POST['judul'];
    $stok  = $_POST['stok'];

    $sql = "INSERT INTO buku (judul, stok) VALUES ('$judul', '$stok')";
    if (mysqli_query($conn, $sql)) {
        header("Location: buku.php?pesan=berhasil_tambah");
    }
}

// Cek apakah ada aksi Hapus melalui GET
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];

    $sql = "DELETE FROM buku WHERE id_buku = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: buku.php?pesan=berhasil_hapus");
    }
}
