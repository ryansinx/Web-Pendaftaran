<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student data from the database
$sql = "SELECT * FROM students WHERE user_id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['name'];
    $student_score = $row['score']; // Assuming 'score' is a column in the 'students' table
} else {
    echo "No student data found.";
    exit();
}

// Define the passing score
$passing_score = 60;

// Check if the student has passed or failed
if ($student_score >= $passing_score) {
    $result = "Lulus";
} else {
    $result = "Tidak Lulus";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Kelulusan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 18px;
            color: #666;
        }
        .status {
            font-weight: bold;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengumuman Kelulusan</h1>
        <p>Nama: <?php echo $student_name; ?></p>
        <p>Nilai: <?php echo $student_score; ?></p>
        <p class='status'>Status: <?php echo $result; ?></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>