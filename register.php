<?php
if (
    isset($_POST['username'], $_POST['password'], $_POST['confirm_password'],
          $_POST['nama'], $_POST['nrp'], $_POST['asal'], $_POST['kelas'])
) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama = $_POST['nama'];
    $nrp = $_POST['nrp'];
    $asal = $_POST['asal'];
    $kelas = $_POST['kelas'];

    if ($password !== $confirm_password) {
        die("Password and Confirm Password do not match.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', '', 'p5_alprog');

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM login WHERE username = ? OR nrp = ?");
    $stmt->bind_param("si", $username, $nrp);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username atau NRP sudah terdaftar.");
    }
    $stmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO login (username, password, nama, nrp, asal, kelas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $username, $hashed_password, $nama, $nrp, $asal, $kelas);

    if ($stmt->execute()) {
        echo "Registrasi berhasil.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
