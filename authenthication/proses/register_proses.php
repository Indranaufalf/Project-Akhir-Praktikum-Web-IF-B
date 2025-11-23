<?php
    require "../../config/connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $nama = $_POST["nama"];
        $kelas = $_POST["kelas"];

        $check = $db->prepare("SELECT id_user FROM user WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            echo "Nama sudah terdaftar!";        
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
            $stmt = $db->prepare("INSERT INTO user (username, password, nama, kelas) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $hashed_password, $nama, $kelas);

            if($stmt->execute()){
                
                header("Location: ../login.php");
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
        $check->close();
    }
?>