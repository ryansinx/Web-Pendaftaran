<?php
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $asal_sma = $_POST['asal_sma'];
    $tahun_tamat = $_POST['tahun_tamat'];
    $nama_orang_tua = $_POST['nama_orang_tua'];
    $jurusan = $_POST['jurusan'];
    $email = $_POST['email'];

    // Membuat nomor pendaftaran otomatis
    $no_pendaftaran = uniqid('REG-');

    // Menyimpan foto yang diambil dari webcam
    $image = $_POST['image'];
    $folderPath = "uploads/";

    // Decode base64 image
    $image_parts = explode(";base64,", $image);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];

    $image_base64 = base64_decode($image_parts[1]);
    $fileName = $no_pendaftaran . '.' . $image_type;

    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);

    // Simpan data ke database
    $sql = "INSERT INTO mahasiswa (no_pendaftaran, nama, tempat_lahir, tanggal_lahir, alamat, asal_sma, tahun_tamat, nama_orang_tua, jurusan, email, foto)
            VALUES ('$no_pendaftaran', '$nama', '$tempat_lahir', '$tanggal_lahir', '$alamat', '$asal_sma', '$tahun_tamat', '$nama_orang_tua', '$jurusan', '$email',  '$fileName')";

if ($conn->query($sql) === TRUE) {
    echo "Pendaftaran berhasil!";
    echo '<div id="countdown">Anda akan dipindahkan ke halaman final setelah <span id="time">3</span> detik.</div>';
    echo '<script>
            let time = 3;
            const countdownElement = document.getElementById("time");
            const interval = setInterval(() => {
                countdownElement.textContent = time--;
                if (time < 0) {
                    clearInterval(interval);
                    window.location.href = "final.php";
                }
            }, 1000);
          </script>';
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Update status has_filled_form
    $update_query = "UPDATE users SET has_filled_form = 1 WHERE email = '$email'";
    $conn->query($update_query);

    $conn->close();
}
?>


