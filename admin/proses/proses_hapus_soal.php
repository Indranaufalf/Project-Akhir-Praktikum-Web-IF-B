<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authentication/login.php");
    exit;
}

if(isset($_GET['id']) && isset($_GET['paket'])){
    
    $id_soal = $_GET['id'];
    $paket_soal = $_GET['paket'];
    
    $stmt_jawaban = $db->prepare("DELETE FROM jawaban WHERE soal_id = ?");
    $stmt_jawaban->bind_param("i", $id_soal);
    $stmt_jawaban->execute();
    
    $stmt_soal = $db->prepare("DELETE FROM soal WHERE id = ?");
    $stmt_soal->bind_param("i", $id_soal);
    
    if($stmt_soal->execute()){
        $_SESSION['pesan'] = "Soal berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus soal!";
    }
    
    $stmt_jawaban->close();
    $stmt_soal->close();
    $db->close();
    
    header("Location: ../pages/kelola_soal.php?paket=$paket_soal");
    exit;
    
} else {
    $_SESSION['pesan'] = "ID soal tidak ditemukan!";
    header("Location: ../pages/kelola_soal.php");
    exit;
}
?>