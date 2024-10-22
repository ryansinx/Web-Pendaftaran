<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database benar

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ambil ID dari parameter URL

    // Query untuk menghapus data
    $deleteQuery = "DELETE FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "ID tidak valid.";
}

header('Location: admin.php'); // Redirect ke admin.php
exit();
?>
