<?php
// Cek apakah ada pesan yang diterima
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
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

        .notification {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
        }

        .notification h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #28a742;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="notification">
        <h1><?php echo htmlspecialchars($message); ?></h1>
        <button type="button" class="btn" onclick="window.location.href='dokter.html'">Kembali</button>
    </div>
</body>
</html>