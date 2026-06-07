<?php
include 'koneksi.php';

// Fungsi bantuan untuk mencetak template HTML SweetAlert
function tampilkanAlert($judul, $teks, $ikon, $url_tujuan)
{
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <title>Memproses...</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>body { font-family: 'Poppins', sans-serif; background-color: #f0edf8; }</style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: '$judul',
                text: '$teks',
                icon: '$ikon',
                confirmButtonColor: '#1e0e60'
            }).then(() => {
                window.location.href = '$url_tujuan';
            });
        </script>
    </body>
    </html>
    ";
    exit;
}

/**
 * PROSES TAMBAH BUKU
 * Dijalankan saat tombol Simpan di buku.php diklik
 */
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {

    // Tangkap data dan amankan dari SQL Injection
    // PERBAIKAN: Tangkap $id_buku yang sebelumnya terlewat
    $id_buku      = mysqli_real_escape_string($conn, $_POST['id_buku']);
    $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
    $genre        = mysqli_real_escape_string($conn, $_POST['genre']);
    $pengarang    = mysqli_real_escape_string($conn, $_POST['pengarang']);
    $penerbit     = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $stok         = mysqli_real_escape_string($conn, $_POST['stok']);

    // Cek apakah ID Buku sudah dipakai (agar tidak bentrok)
    $cek_id = mysqli_query($conn, "SELECT id_buku FROM buku WHERE id_buku = '$id_buku'");
    if (mysqli_num_rows($cek_id) > 0) {
        tampilkanAlert('Gagal!', 'Kode/ID Buku tersebut sudah digunakan. Silakan gunakan kode lain.', 'warning', 'buku.php');
    } else {
        // PERBAIKAN: Masukkan id_buku ke dalam Query SQL
        $sql = "INSERT INTO buku (id_buku, judul, genre, pengarang, penerbit, tahun_terbit, stok) 
                VALUES ('$id_buku', '$judul', '$genre', '$pengarang', '$penerbit', '$tahun_terbit', '$stok')";

        if (mysqli_query($conn, $sql)) {
            // Berhasil simpan
            tampilkanAlert('Berhasil!', 'Data buku baru berhasil ditambahkan ke katalog.', 'success', 'buku.php');
        } else {
            // Gagal simpan
            $error_msg = mysqli_error($conn);
            // Tambahkan addslashes agar error message aman ditampilkan di alert JS
            $aman_error = addslashes($error_msg);
            tampilkanAlert('Gagal Sistem!', 'Terjadi kesalahan: ' . $aman_error, 'error', 'buku.php');
        }
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
        // Berhasil hapus
        tampilkanAlert('Terhapus!', 'Buku berhasil dihapus permanen dari sistem.', 'success', 'buku.php');
    } else {
        // Gagal hapus (biasanya karena terkait foreign key di tabel peminjaman)
        tampilkanAlert('Tidak Dapat Dihapus!', 'Buku ini masih memiliki riwayat peminjaman oleh siswa.', 'warning', 'buku.php');
    }
}

/**
 * JIKA DIAKSES LANGSUNG TANPA AKSI
 */
if (!isset($_POST['aksi']) && !isset($_GET['aksi'])) {
    header("Location: buku.php");
    exit;
}
