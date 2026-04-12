<?php
// Memulai session
session_start();

// Menghapus semua data session
session_destroy();

// Mengarahkan kembali ke halaman login dengan pesan logout
header("location:login.php?pesan=logout");
exit;
