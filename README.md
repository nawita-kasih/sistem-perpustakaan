# Librarify: Sistem Informasi Perpustakaan Sekolah

Librarify adalah sistem informasi manajemen perpustakaan berbasis web. Aplikasi ini dibangun menggunakan PHP Native dan MySQL. Admin menggunakan sistem ini untuk mengelola inventaris buku, meregistrasi akun siswa, dan memproses sirkulasi peminjaman. Sistem ini juga menghitung denda keterlambatan secara otomatis.

## Fitur Utama

### Panel Admin

- **Kelola Buku:** Tambah, edit, cari, dan hapus data buku dari katalog.
- **Kelola Siswa:** Daftarkan siswa baru dan kelola kredensial akses.
- **Sirkulasi Pinjam:** Catat peminjaman buku dan pantau tenggat waktu.
- **Sirkulasi Kembali:** Proses pengembalian buku dan terapkan denda keterlambatan secara dinamis.
- **Pengaturan Sistem:** Atur batas hari peminjaman dan nominal denda per hari.
- **Laporan:** Cetak laporan seluruh riwayat transaksi perpustakaan.

### Panel Siswa

- **Katalog Buku:** Jelajahi daftar buku beserta informasi stok yang tersedia.
- **Buku Saya:** Pantau status buku yang sedang dipinjam beserta tenggat waktunya.
- **Riwayat Pinjam:** Lihat arsip seluruh transaksi buku terdahulu.
- **Profil:** Perbarui informasi kontak dan kata sandi akun.

## Persyaratan Sistem

- PHP 8.x
- MySQL / MariaDB
- Laragon atau XAMPP
- Koneksi internet (untuk memuat library Bootstrap dan ikon)

## Langkah Instalasi

1.  Jalankan modul Apache dan MySQL pada aplikasi Laragon atau XAMPP Anda.
2.  Salin seluruh folder proyek ini ke dalam direktori web server. Gunakan folder `www` jika Anda menggunakan Laragon atau folder `htdocs` jika Anda menggunakan XAMPP.
3.  Buka peramban web dan akses antarmuka manajemen database (misalnya: `http://localhost/phpmyadmin`).
4.  Buat database baru dengan nama `perpustakaan_sekolah`.
5.  Buka database tersebut, pilih menu **Import**, dan unggah file `perpustakaan_sekolah (2).sql` yang berada di dalam folder `database`.
6.  Pastikan konfigurasi koneksi pada file `koneksi.php` sudah sesuai dengan environment server lokal Anda.
7.  Buka peramban web dan akses aplikasi melalui URL `http://localhost/sistem-perpustakaan` (sesuaikan bagian belakang URL dengan nama folder proyek Anda).

## Akses Login Default

Anda dapat mencoba sistem ini menggunakan akun bawaan berikut.

**Akun Administrator:**

- **Username:** `admin`
- **Password:** `admin123`

**Akun Siswa (Contoh):**

- **Username:** `231011`
- **Password:** `12345`

## Struktur Database

Sistem ini menggunakan relasi antar tabel untuk menjaga integritas data.

- `anggota`: Menyimpan data profil siswa.
- `buku`: Menyimpan detail dan stok koleksi buku.
- `peminjaman`: Mencatat log transaksi, tanggal pinjam, dan denda.
- `pengaturan`: Menyimpan aturan batas pinjam dan tarif denda.
- `users`: Menyimpan kredensial autentikasi untuk login.

## Teknologi

- Backend: PHP Native
- Database: MySQL / MariaDB
- Frontend: HTML5, CSS3, Bootstrap 5
- Alert Interaktif: SweetAlert2
