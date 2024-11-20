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
    // Menambahkan data Pasien
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $nama = $_POST['nama'];
        $jenisKelamin = $_POST['jenisKelamin'];
        $alamat = $_POST['alamat'];
        $namaDokter = $_POST['namaDokter'];
        $namaObat = $_POST['namaObat'];
        $diagnosa = $_POST['diagnosa'];

        // Validasi jenis kelamin
        if ($jenisKelamin !== "laki-laki" && $jenisKelamin !== "perempuan") {
            die("Jenis kelamin tidak valid.");
        }

        // Menyiapkan dan mengeksekusi query untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO pasien (nama, jenisKelamin, alamat, namaDokter, namaObat, diagnosa) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nama, $jenisKelamin, $alamat, $namaDokter, $namaObat, $diagnosa);

        if ($stmt->execute()) {
            header("Location: notifikasipasien.php?message=Data berhasil ditambahkan");
            exit();
        } else {
            header("Location: notifikasipasien.php?message=Gagal menambahkan data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Mengedit data pasien
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $nama = $_POST['nama'];
        $jenisKelamin = $_POST['jenisKelamin'];
        $alamat = $_POST['alamat'];
        $namaDokter = $_POST['namaDokter'];
        $namaObat = $_POST['namaObat'];
        $diagnosa = $_POST['diagnosa'];
        $kodePerawatan = $_POST['kodePerawatan']; // Ambil kodePerawatan dari POST

        // Validasi jenis kelamin
        if ($jenisKelamin !== "laki-laki" && $jenisKelamin !== "perempuan") {
            die("Jenis kelamin tidak valid.");
        }

        // Menyiapkan dan mengeksekusi query untuk mengedit data
        $stmt = $conn->prepare("UPDATE pasien SET nama = ?, jenisKelamin = ?, alamat = ?, namaDokter = ?, namaObat = ?, diagnosa = ? WHERE kodePerawatan = ?");
        $stmt->bind_param("ssssssi", $nama, $jenisKelamin, $alamat, $namaDokter, $namaObat, $diagnosa, $kodePerawatan);

        if ($stmt->execute()) {
            header("Location: notifikasipasien.php?message=Data berhasil diubah");
            exit();
        } else {
            header("Location: notifikasipasien.php?message=Gagal mengubah data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }

    // Menghapus data pasien
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $kodePerawatan = intval($_POST['kodePerawatan']);

        // Menyiapkan dan mengeksekusi query untuk menghapus data
        $stmt = $conn->prepare("DELETE FROM pasien WHERE kodePerawatan = ?");
        $stmt->bind_param("i", $kodePerawatan);

        if ($stmt->execute()) {
            header("Location: notifikasipasien.php?message=Data berhasil dihapus");
            exit();
        } else {
            header("Location: notifikasipasien.php?message=Gagal menghapus data: " . $stmt->error);
            exit();
        }
        $stmt->close();
    }
}

// Mengambil data pasien
$sql = "SELECT * FROM pasien";
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