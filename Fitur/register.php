<?php
    $username = $_POST['username']; 
    $password = $_POST['password'];
    $email = $_POST['email'];

    $conn = new mysqli('localhost','root',"", 'kesehatan');
    if($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    } else {
        $smt = $conn->prepare("INSERT INTO registrasi(username, password, email) VALUES(?, ?, ?)");
        $smt->bind_param("sss", $username, $password, $email); 
        $smt->execute();


        header("Location: notifikasi.html");
        exit(); 

        $smt->close();
        $conn->close();
    }
?>