<?php
session_start(); // Memulai sesi

// Cek apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
    header('Location: dasboard.php'); // Redirect ke dasboard.php jika belum login
    exit(); // Pastikan untuk menghentikan eksekusi script setelah redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mahasiswa Baru</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Style untuk modal */
        .modal {
            display: none; /* Sembunyikan modal secara default */
            position: fixed; /* Tetap di tempat */
            z-index: 1000; /* Di atas konten lain */
            left: 0;
            top: 0;
            width: 100%; /* Lebar penuh */
            height: 100%; /* Tinggi penuh */
            overflow: auto; /* Tambahkan scroll jika diperlukan */
            background-color: rgba(0, 0, 0, 0.8); /* Latar belakang gelap */
        }

        /* Konten modal */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* Atur margin */
            padding: 20px;
            border: 1px solid #888;
            width: 300px; /* Lebar modal */
            text-align: center; /* Rata tengah */
            position: relative; /* Untuk posisi elemen */
        }

        /* Style untuk video dan canvas */
        #video, #canvas {
            width: 100%; /* Lebar penuh */
            height: auto; /* Tinggi otomatis */
            border-radius: 5px;
        }

        /* Style untuk tombol */
        #snap {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #snap:hover {
            background-color: #0056b3;
        }

        #capturedImage {
            border: 2px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
            width: 100%; /* Lebar penuh */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Mahasiswa Baru</h2>
        <form action="process.php" method="post" enctype="multipart/form-data">
            <!-- Form Fields -->
            <label>Nama:</label>
            <input type="text" name="nama" required><br>

            <label>Tempat Lahir:</label>
            <input type="text" name="tempat_lahir" required><br>

            <label>Tanggal Lahir:</label>
            <input type="date" name="tanggal_lahir" required><br>

            <label>Alamat:</label>
            <input type="text" name="alamat" required><br>

            <label>Asal SMA:</label>
            <input type="text" name="asal_sma" required><br>

            <label>Tahun Tamat:</label>
            <input type="number" name="tahun_tamat" required><br>

            <label>Nama Orang Tua:</label>
            <input type="text" name="nama_orang_tua" required><br>

            <label>Jurusan:</label>
            <input type="text" name="jurusan" required><br>

            <label>Alamat Email:</label>
            <input type="text" name="email" required><br>

            <!-- Tombol untuk mengaktifkan kamera -->
            <button type="button" id="enableCamera">Aktifkan Kamera</button>

            <!-- Preview foto setelah diambil -->
            <div id="preview" style="display: none;">
                <h4>Preview Foto:</h4>
                <img id="capturedImage" src="#" alt="Foto Preview" />
            </div>

            <input type="hidden" name="image" class="image-tag">
            <!-- Submit Button -->
            <button type="submit">Daftar</button>
        </form>
    </div>

    <!-- Modal untuk kamera -->
    <div id="cameraModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <label>Ambil Foto:</label>
            <video id="video" autoplay></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <br>
            <button type="button" id="snap">Ambil Foto</button>
        </div>
    </div>

    <!-- Script untuk menangani webcam -->
    <script>
        // Akses elemen HTML
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var snap = document.getElementById('snap');
        var imageInput = document.querySelector('.image-tag');
        var enableCameraButton = document.getElementById('enableCamera');
        var previewImage = document.getElementById('capturedImage');
        var previewContainer = document.getElementById('preview');
        var cameraModal = document.getElementById('cameraModal');
        var closeModal = document.getElementsByClassName("close")[0];

        // Mengaktifkan kamera dan menampilkan modal
        enableCameraButton.addEventListener("click", function() {
            cameraModal.style.display = "block"; // Menampilkan modal
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                })
                .catch(function(err) {
                    console.error("Error: " + err);
                });
        });

        // Menangkap foto
        snap.addEventListener("click", function() {
            var context = canvas.getContext('2d');
            // Ambil gambar dari video
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            var dataURL = canvas.toDataURL('image/jpeg');
            imageInput.value = dataURL;  // Simpan data foto di input hidden

            // Tampilkan hasil tangkapan
            previewImage.src = dataURL;
            previewContainer.style.display = "block"; // Tampilkan preview

            // Tampilkan gambar di canvas
            canvas.style.display = "block"; // Tampilkan canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height); // Menggambar gambar dari video
        });

        // Menutup modal
        closeModal.onclick = function() {
            cameraModal.style.display = "none"; // Sembunyikan modal
            var stream = video.srcObject;
            if (stream) {
                var tracks = stream.getTracks();
                tracks.forEach(function(track) {
                    track.stop(); // Hentikan semua track
                });
                video.srcObject = null; // Hentikan video
            }
        }

        // Tutup modal jika pengguna mengklik di luar modal
        window.onclick = function(event) {
            if (event.target == cameraModal) {
                cameraModal.style.display = "none"; // Sembunyikan modal
                var stream = video.srcObject;
                if (stream) {
                    var tracks = stream.getTracks();
                    tracks.forEach(function(track) {
                        track.stop(); // Hentikan semua track
                    });
                    video.srcObject = null; // Hentikan video
                }
            }
        }
    </script>
</body>
</html>
