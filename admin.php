<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database benar

// Cek apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['email']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: dasboard.php'); // Redirect ke halaman login jika tidak diizinkan
    exit();
}

// Tambah logout jika tombol diklik
if (isset($_GET['logout'])) {
    session_destroy(); // Menghancurkan session
    header('Location: dasboard.php'); // Redirect ke dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Mahasiswa Baru</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang */
        }
        .container {
            max-width: 1200px; /* Lebar maksimum container */
            margin: 30px auto; /* Margin vertikal dan horizontal */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: white; /* Warna latar belakang kontainer */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff; /* Warna judul */
        }
        th {
            background-color: #007bff; /* Warna latar belakang header */
            color: white; /* Warna teks header */
        }
        td img {
            border-radius: 5px; /* Sudut gambar bulat */
        }
        .logout-btn {
            margin-bottom: 20px; /* Jarak antara tombol logout dan konten */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="?logout=true" class="btn btn-danger logout-btn">Logout</a>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Hapus setelah ditampilkan
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']); // Hapus setelah ditampilkan
                ?>
            </div>
        <?php endif; ?>
        <h2>Data Mahasiswa Baru</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No Pendaftaran</th>
                        <th>Nama</th>
                        <th>TTL</th>
                        <th>Alamat</th>
                        <th>Asal SMA</th>
                        <th>Tahun Tamat</th>
                        <th>Nama Orang Tua</th>
                        <th>Jurusan</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM mahasiswa");

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['no_pendaftaran']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['tempat_lahir']}, {$row['tanggal_lahir']}</td>
                                    <td>{$row['alamat']}</td>
                                    <td>{$row['asal_sma']}</td>
                                    <td>{$row['tahun_tamat']}</td>
                                    <td>{$row['nama_orang_tua']}</td>
                                    <td>{$row['jurusan']}</td>
                                    <td><img src='uploads/{$row['foto']}' width='100' alt='Foto'></td>
                                    <td>
                                        <a href='update_mahasiswa.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                                        <a href='delete_mahasiswa.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>Tidak ada data.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
