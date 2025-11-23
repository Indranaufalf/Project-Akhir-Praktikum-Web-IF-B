<?php
    require "../../config/connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        session_start();

        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $row = $result->fetch_assoc();

            if(password_verify($password, $row["password"])){
                $_SESSION["username"] = $row["username"];
                $_SESSION["kelas"] = $row["kelas"];
                $_SESSION["id_user"] = $row["id_user"];
                $_SESSION["role"] = $row["role"]; 

                if($row["role"] == "admin"){
                    header("Location: ../../admin/pages/dashboard_admin.php");
                } else {
                    header("Location: ../../user/pages/dashboard.php");
                }
                exit;
            } else {
                header("Location: ../login.php?error=password");
                exit;
            }
        } else {
            header("Location: ../login.php?error=username");
            exit;
        }
    }
?>