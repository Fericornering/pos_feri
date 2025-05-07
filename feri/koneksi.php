<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pos_db";

// Membuat koneksi ke database
$koneksi = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
