<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password']; // Pastikan Anda meng-hash password sebelum menyimpannya
    // Tambahkan kode untuk memproses input lainnya seperti nama, dll.

    // Cek apakah email sudah terdaftar
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika email sudah ada, tampilkan alert
        echo "<script>alert('Email sudah digunakan. Silakan gunakan email lain.'); window.history.back();</script>";
    } else {
        // Jika email belum ada, lakukan insert
        $insertQuery = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash password
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            // Pendaftaran sukses
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='dasboard.php';</script>";
        } else {
            // Jika terjadi kesalahan dalam query
            echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.'); window.history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
