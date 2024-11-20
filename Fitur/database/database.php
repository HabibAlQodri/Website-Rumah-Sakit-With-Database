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
    // Menambahkan data dokter
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $namaDokter = $_POST['namaDokter'];
        $spesialis = $_POST['spesialis'];

        // Menyiapkan dan mengeksekusi query untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO dokter (namaDokter, spesialis) VALUES (?, ?)");
        $stmt->bind_param("ss", $namaDokter, $spesialis);

        if ($stmt->execute()) {
            header("Location: notifikasiadd.php?message=Data berhasil ditambahkan");
            exit();
        } else {
            header("Location: notifikasiadd.php?message=Gagal menambahkan data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Mengedit data dokter
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $idDokter = $_POST['idDokter'];
        $namaDokter = $_POST['namaDokter'];
        $spesialis = $_POST['spesialis'];

        // Menyiapkan dan mengeksekusi query untuk mengedit data
        $stmt = $conn->prepare("UPDATE dokter SET namaDokter = ?, spesialis = ? WHERE idDokter = ?");
        $stmt->bind_param("ssi", $namaDokter, $spesialis, $idDokter);

        if ($stmt->execute()) {
            header("Location: notifikasiadd.php?message=Data berhasil diubah");
            exit();
        } else {
            header("Location: notifikasiadd.php?message=Gagal mengubah data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Menghapus data dokter
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $idDokter = intval($_POST['idDokter']);
    
        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $stmt = $conn->prepare("DELETE FROM dokter WHERE idDokter = ?");
        $stmt->bind_param("i", $idDokter);
    
        if ($stmt->execute()) {
            header("Location: notifikasiadd.php?message=Data berhasil dihapus");
            exit();
        } else {
            header("Location: notifikasiadd.php?message=Gagal menghapus data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }
}

// Mengambil data dokter
$sql = "SELECT idDokter, namaDokter, spesialis FROM dokter";
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