<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database benar

// Cek apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['email']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: dasboard.php'); // Redirect ke halaman login jika tidak diizinkan
    exit();
}

// Debugging: Cek apakah ID diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    // Ambil data mahasiswa dari database
    $query = "SELECT * FROM mahasiswa WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Data tidak ditemukan.'); window.location.href='admin.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak diterima.'); window.location.href='admin.php';</script>";
    exit();
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $asal_sma = $_POST['asal_sma'];
    $tahun_tamat = $_POST['tahun_tamat'];
    $nama_orang_tua = $_POST['nama_orang_tua'];
    $jurusan = $_POST['jurusan'];
    
    // Update data mahasiswa
    $updateQuery = "UPDATE mahasiswa SET nama=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, asal_sma=?, tahun_tamat=?, nama_orang_tua=?, jurusan=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssssssi", $nama, $tempat_lahir, $tanggal_lahir, $alamat, $asal_sma, $tahun_tamat, $nama_orang_tua, $jurusan, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Data Mahasiswa</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" class="form-control" value="<?php echo isset($row) ? $row['nama'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-control" value="<?php echo isset($row) ? $row['tempat_lahir'] : ''; ?>" required>
            </div>
              <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo isset($row) ? $row['tanggal_lahir'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" name="alamat" class="form-control" value="<?php echo isset($row) ? $row['alamat'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="asal_sma">Asal SMA:</label>
                <input type="text" name="asal_sma" class="form-control" value="<?php echo isset($row) ? $row['asal_sma'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="tahun_tamat">Tahun Tamat:</label>
                <input type="number" name="tahun_tamat" class="form-control" value="<?php echo isset($row) ? $row['tahun_tamat'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_orang_tua">Nama Orang Tua:</label>
                <input type="text" name="nama_orang_tua" class="form-control" value="<?php echo isset($row) ? $row['nama_orang_tua'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="jurusan">Jurusan:</label>
                <input type="text" name="jurusan" class="form-control" value="<?php echo isset($row) ? $row['jurusan'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
