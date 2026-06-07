<?php
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_pinjam = $_GET['id'];
$id_buku   = $_GET['id_buku'];
$tgl_sekarang = date('Y-m-d');

// 1. Ambil data peminjaman & pengaturan untuk hitung denda dinamis
$sql_cek = "SELECT p.*, s.denda_per_hari as denda_default, s.batas_hari_pinjam FROM peminjaman p CROSS JOIN pengaturan s WHERE p.id_pinjam = '$id_pinjam' AND s.id = 1";
$query_cek = mysqli_query($conn, $sql_cek);
$data = mysqli_fetch_assoc($query_cek);

// Tentukan deadline (Berdasarkan tgl_pinjam + batas_hari)
$tgl_pinjam = $data['tgl_pinjam'];

// PERBAIKAN ERROR: Cek ketersediaan array key menggunakan isset()
$batas_hari_db = isset($data['batas_hari']) ? $data['batas_hari'] : 0;
$batas = ($batas_hari_db > 0) ? $batas_hari_db : $data['batas_hari_pinjam'];

$tgl_deadline = date('Y-m-d', strtotime($tgl_pinjam . " +$batas days"));

// 2. Hitung Denda secara Dinamis
$denda = 0;
$selisih = 0;
if (strtotime($tgl_sekarang) > strtotime($tgl_deadline)) {
    $detik = strtotime($tgl_sekarang) - strtotime($tgl_deadline);
    $selisih = floor($detik / (60 * 60 * 24));

    // PERBAIKAN ERROR: Cek ketersediaan array key menggunakan isset()
    $denda_per_hari_db = isset($data['denda_per_hari']) ? $data['denda_per_hari'] : 0;
    $nominal_harian = ($denda_per_hari_db > 0) ? $denda_per_hari_db : $data['denda_default'];

    $denda = $selisih * $nominal_harian;
}

// 3. Update status peminjaman (NAMA KOLOM DISESUAIKAN DENGAN DATABASE: tgl_dikembalikan)
$update_pinjam = mysqli_query($conn, "UPDATE peminjaman SET tgl_dikembalikan = '$tgl_sekarang', denda = '$denda', status = 'kembali' WHERE id_pinjam = '$id_pinjam'");

// 4. Tambahkan kembali stok buku
$update_stok = mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id_buku = '$id_buku'");

if ($update_pinjam && $update_stok) {
    // Kita buatkan format HTML khusus untuk memanggil SweetAlert dari dalam PHP
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Memproses...</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap' rel='stylesheet'>
        <style>body { font-family: 'Poppins', sans-serif; background-color: #f0edf8; }</style>
    </head>
    <body>
        <script>
    ";

    if ($denda > 0) {
        $denda_f = number_format($denda, 0, ',', '.');
        echo "
            Swal.fire({
                title: 'Buku Dikembalikan!',
                text: 'Terlambat $selisih hari. Denda: Rp $denda_f',
                icon: 'warning',
                confirmButtonColor: '#1e0e60'
            }).then(() => {
                window.location = 'data_pinjam.php';
            });
        ";
    } else {
        echo "
            Swal.fire({
                title: 'Berhasil!',
                text: 'Buku dikembalikan tepat waktu. Terima kasih!',
                icon: 'success',
                confirmButtonColor: '#1e0e60'
            }).then(() => {
                window.location = 'data_pinjam.php';
            });
        ";
    }

    echo "
        </script>
    </body>
    </html>
    ";
} else {
    echo "Gagal memproses: " . mysqli_error($conn);
}
