<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $id_paket = $_POST['id_paket'];
    $judul = $_POST['judul'];
    $mata_pelajaran = $_POST['mata_pelajaran'];
    $kelas = $_POST['kelas'];
    $jumlah_soal = $_POST['jumlah_soal'];
    
    $stmt = $db->prepare("UPDATE paket_soal SET 
                         judul = ?,
                         mata_pelajaran = ?,
                         kelas = ?,
                         jumlah_soal = ?
                         WHERE id_paket = ?");
    $stmt->bind_param("ssiii", $judul, $mata_pelajaran, $kelas, $jumlah_soal, $id_paket);
    
    if($stmt->execute()){
        $_SESSION['pesan'] = "Paket soal berhasil diupdate!";
    } else {
        $_SESSION['pesan'] = "Gagal mengupdate paket soal!";
    }
    
    $stmt->close();
    $db->close();
    
    header("Location: ../pages/kelola_paket.php");
    exit;
}

header("Location: ../pages/kelola_paket.php");
exit;
?>