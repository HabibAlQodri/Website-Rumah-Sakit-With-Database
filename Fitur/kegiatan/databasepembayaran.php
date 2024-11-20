<?php
// koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kesehatan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Validasi status pembayaran
$validStatuses = ['Belum dibayar', 'Sudah dibayar', 'Sedang dalam proses'];

// Cek apakah ada data yang dikirim untuk ditambahkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Menambahkan data pembayaran
    if ($action === 'add') {
        $statusPembayaran = $_POST['statusPembayaran'] ?? '';
        $totalBiaya = $_POST['totalBiaya'] ?? 0;
        $tanggalPembayaran = $_POST['tanggalPembayaran'] ?? '';

        // Validasi status pembayaran
        if (!in_array($statusPembayaran, $validStatuses)) {
            die("Status pembayaran tidak valid.");
        }

        // Menyiapkan dan mengeksekusi query untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO pembayaran (statusPembayaran, totalBiaya, tanggalPembayaran) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $statusPembayaran, $totalBiaya, $tanggalPembayaran);

        if ($stmt->execute()) {
            header("Location: pembayaran.html?message=Data berhasil ditambahkan");
            exit();
        } else {
            header("Location: pembayaran.html?error=Gagal menambahkan data: " . $stmt->error);
            exit();
        }
    }

    // Mengedit data pembayaran
    elseif ($action === 'edit') {
        $kodePembayaran = $_POST['kodePembayaran'] ?? '';
        $statusPembayaran = $_POST['statusPembayaran'] ?? '';
        $totalBiaya = $_POST['totalBiaya'] ?? 0;
        $tanggalPembayaran = $_POST['tanggalPembayaran'] ?? '';

        // Validasi status pembayaran
        if (!in_array($statusPembayaran, $validStatuses)) {
            die("Status pembayaran tidak valid.");
        }

        // Menyiapkan dan mengeksekusi query untuk mengedit data
        $stmt = $conn->prepare("UPDATE pembayaran SET statusPembayaran = ?, totalBiaya = ?, tanggalPembayaran = ? WHERE kodePembayaran = ?");
        $stmt->bind_param("sisi", $statusPembayaran, $totalBiaya, $tanggalPembayaran, $kodePembayaran);

        if ($stmt->execute()) {
            header("Location: pembayaran.html?message=Data berhasil diubah");
            exit();
        } else {
            header("Location: pembayaran.html?error=Gagal mengubah data: " . $stmt->error);
            exit();
        }
    }

    // Menghapus data pembayaran
    elseif ($action === 'delete') {
        $idPembayaran = intval($_POST['idPembayaran'] ?? 0);
        
        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $stmt = $conn->prepare("DELETE FROM pembayaran WHERE idPembayaran = ?");
        $stmt->bind_param("i", $idPembayaran);
        
        if ($stmt->execute()) {
            header("Location: pembayaran.html?message=Data berhasil dihapus");
            exit();
        } else {
            header("Location: pembayaran.html?error=Gagal menghapus data: " . $stmt->error);
            exit();
        }
    }
}
// Mengambil data pembayaran
$sql = "SELECT * FROM pembayaran";
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