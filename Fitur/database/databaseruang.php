<?php
// koneksi ke database
$servername = "localhost"; // ganti dengan server database Anda
$username = "root"; // ganti dengan username database Anda
$password = ""; // ganti dengan password database Anda
$dbname = "kesehatan"; // ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah ada data yang dikirim untuk ditambahkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menambahkan data ruang
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $namaRuang = $_POST['namaRuang'];

        // Menyiapkan dan mengeksekusi query untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO ruang (namaRuang) VALUES (?)");
        $stmt->bind_param("s", $namaRuang);

        if ($stmt->execute()) {
            header("Location: notifikasiruang.php?message=Data berhasil ditambahkan");
            exit();
        } else {
            header("Location: notifikasiruang.php?message=Gagal menambahkan data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Mengedit data ruang
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $kodeRuang = $_POST['kodeRuang'];
        $namaRuang = $_POST['namaRuang'];

        // Menyiapkan dan mengeksekusi query untuk mengedit data
        $stmt = $conn->prepare("UPDATE ruang SET namaRuang = ? WHERE kodeRuang = ?");
        $stmt->bind_param("si", $namaRuang, $kodeRuang);

        if ($stmt->execute()) {
            header("Location: notifikasiruang.php?message=Data berhasil diubah");
            exit();
        } else {
            header("Location: notifikasiruang.php?message=Gagal mengubah data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Menghapus data ruang
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $kodeRuang = intval($_POST['kodeRuang']);
    
        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $stmt = $conn->prepare("DELETE FROM ruang WHERE kodeRuang = ?");
        $stmt->bind_param("i", $kodeRuang);
    
        if ($stmt->execute()) {
            header("Location: notifikasiruang.php?message=Data berhasil dihapus");
            exit();
        } else {
            header("Location: notifikasiruang.php?message=Gagal menghapus data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }
}

// Mengambil data ruang
$sql = "SELECT * FROM ruang";
$result = $conn->query($sql);

// Menyiapkan data dalam format JSON
$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$conn->close();

// Mengubah data menjadi format JSON
echo json_encode($data);
?>