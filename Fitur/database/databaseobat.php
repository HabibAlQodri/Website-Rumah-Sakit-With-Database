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
    // Menambahkan data obat
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $namaObat = $_POST['namaObat'];

        // Menyiapkan dan mengeksekusi query untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO obat (namaObat) VALUES (?)");
        $stmt->bind_param("s", $namaObat);

        if ($stmt->execute()) {
            header("Location: notifikasiobat.php?message=Data berhasil ditambahkan");
            exit();
        } else {
            header("Location: notifikasiobat.php?message=Gagal menambahkan data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Mengedit data obat
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $kodeObat = $_POST['kodeObat'];
        $namaObat = $_POST['namaObat'];

        // Menyiapkan dan mengeksekusi query untuk mengedit data
        $stmt = $conn->prepare("UPDATE obat SET namaObat = ? WHERE kodeObat = ?");
        $stmt->bind_param("si", $namaObat, $kodeObat);

        if ($stmt->execute()) {
            header("Location: notifikasiobat.php?message=Data berhasil diubah");
            exit();
        } else {
            header("Location: notifikasiobat.php?message=Gagal mengubah data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Menghapus data obat
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $kodeObat = intval($_POST['kodeObat']);
    
        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $stmt = $conn->prepare("DELETE FROM obat WHERE kodeObat = ?");
        $stmt->bind_param("i", $kodeObat);
    
        if ($stmt->execute()) {
            header("Location: notifikasiobat.php?message=Data berhasil dihapus");
            exit();
        } else {
            header("Location: notifikasiobat.php?message=Gagal menghapus data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }
}

// Mengambil data obat
$sql = "SELECT kodeObat, namaObat FROM obat";
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