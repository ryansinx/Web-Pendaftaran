<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pendaftaran_mahasiswa";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>