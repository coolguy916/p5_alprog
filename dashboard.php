<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke login page
    header("Location: login.html");
    exit;
}

// Koneksi database
$conn = new mysqli('localhost', 'root', '', 'p5_alprog');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$query = "SELECT nama, nrp, asal, kelas FROM login WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User tidak ditemukan.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css?v=2"> <!-- Cache busting -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 1rem;
        }
        .info {
            text-align: left;
            margin-top: 1rem;
        }
        .info p {
            margin: 0.3rem 0;
        }
        .logout {
            margin-top: 1.5rem;
        }
        .logout a {
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Welcome, <?= htmlspecialchars($user['nama']) ?>!</h2>
        <p>You've successfully logged in.</p>

        <div class="info">
            <img src="" alt="">
            <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
            <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
            <p><strong>NRP:</strong> <?= htmlspecialchars($user['nrp']) ?></p>
            <p><strong>Asal:</strong> <?= htmlspecialchars($user['asal']) ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($user['kelas']) ?></p>
        </div>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
