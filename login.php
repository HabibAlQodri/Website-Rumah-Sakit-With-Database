<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli('localhost', 'root', '', 'kesehatan');

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM registrasi WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.html"); 
        exit();
    } else {
        echo "Username atau password salah.";
    }

    $stmt->close();
    $conn->close();
}
?>