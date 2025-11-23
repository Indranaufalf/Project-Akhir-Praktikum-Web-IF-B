<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $judul = $_POST['judul'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $kelas = $_POST['kelas'];
    $jumlah_soal = $_POST['jumlah_soal'];
    
    $stmt = $db->prepare("INSERT INTO paket_soal (judul, mata_pelajaran, kelas, jumlah_soal) 
                         VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $judul, $mata_pelajaran, $kelas, $jumlah_soal);
    
    if($stmt->execute()){
        $_SESSION['pesan'] = "Paket soal berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan paket soal!";
    }
    
    $stmt->close();
    $db->close();
    
    header("Location: ../pages/kelola_paket.php");
    exit;
}

header("Location: ../pages/kelola_paket.php");
exit;
?>