<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database benar

// Cek apakah ada error dari session
$error_message = '';
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Hapus error setelah ditampilkan
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password']; 

        // Query untuk mendapatkan data user berdasarkan email
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Simpan data user di session
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = ($user['role'] === 'admin'); // Simpan status admin

                // Cek role pengguna
                if ($user['role'] == 'admin') {
                    header('Location: admin.php'); // Redirect ke halaman admin
                    exit();
                } elseif ($user['role'] == 'mahasiswa') {
                    // Cek apakah mahasiswa sudah mengisi form
                    if ($user['has_filled_form'] == 1) {
                        header('Location: final.php'); // Redirect ke final.php jika sudah mengisi form
                        exit();
                    } else {
                        header('Location: index.php'); // Redirect ke index.php jika belum mengisi form
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Peran pengguna tidak dikenali!";
                    header('Location: login_user.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Password salah!";
                header('Location: login_user.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Email tidak ditemukan!";
            header('Location: login_user.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Form tidak lengkap!";
        header('Location: login_user.php');
        exit();
    }
}
?>
