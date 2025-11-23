<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

if(isset($_GET['id'])){
    
    $id_paket = $_GET['id'];
    
    $stmt_nilai = $db->prepare("DELETE FROM nilai WHERE soal_paket = ?");
    $stmt_nilai->bind_param("i", $id_paket);
    $stmt_nilai->execute();
    
    $stmt_get_soal = $db->prepare("SELECT id FROM soal WHERE paket_soal = ?");
    $stmt_get_soal->bind_param("i", $id_paket);
    $stmt_get_soal->execute();
    $result_soal = $stmt_get_soal->get_result();
    
    $stmt_jawaban = $db->prepare("DELETE FROM jawaban WHERE soal_id = ?");
    while($row = $result_soal->fetch_assoc()){
        $id_soal = $row['id'];
        $stmt_jawaban->bind_param("i", $id_soal);
        $stmt_jawaban->execute();
    }
    
    $stmt_soal = $db->prepare("DELETE FROM soal WHERE paket_soal = ?");
    $stmt_soal->bind_param("i", $id_paket);
    $stmt_soal->execute();
    
    $stmt_paket = $db->prepare("DELETE FROM paket_soal WHERE id_paket = ?");
    $stmt_paket->bind_param("i", $id_paket);
    
    if($stmt_paket->execute()){
        $_SESSION['pesan'] = "Paket soal berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus paket soal: " . $db->error;
    }
    
    $stmt_nilai->close();
    $stmt_get_soal->close();
    $stmt_jawaban->close();
    $stmt_soal->close();
    $stmt_paket->close();
    
} else {
    $_SESSION['pesan'] = "ID paket tidak ditemukan!";
}

$db->close();
header("Location: ../pages/kelola_paket.php");
exit;
?>