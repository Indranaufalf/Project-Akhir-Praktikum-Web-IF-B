<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $paket_soal = $_POST['paket_soal'];
    $soal = $_POST['soal'];
    $pilihan_a = $_POST['pilihan_a'];
    $pilihan_b = $_POST['pilihan_b'];
    $pilihan_c = $_POST['pilihan_c'];
    $pilihan_d = $_POST['pilihan_d'];
    $jawaban_benar = $_POST['jawaban_benar'];
    $bobot = $_POST['bobot'];
    $pembahasan = $_POST['pembahasan'];
    
    $stmt_paket = $db->prepare("SELECT kelas, mata_pelajaran FROM paket_soal WHERE id_paket = ?");
    $stmt_paket->bind_param("i", $paket_soal);
    $stmt_paket->execute();
    $result = $stmt_paket->get_result();
    $data_paket = $result->fetch_assoc();
    
    $kelas = $data_paket['kelas'];
    $mata_pelajaran = $data_paket['mata_pelajaran'];
    
    $stmt = $db->prepare("INSERT INTO soal (kelas, mata_pelajaran, soal, paket_soal, pilihan_a, pilihan_b, pilihan_c, pilihan_d, jawaban_benar, pembahasan, bobot) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssi", $kelas, $mata_pelajaran, $soal, $paket_soal, 
                      $pilihan_a, $pilihan_b, $pilihan_c, $pilihan_d, 
                      $jawaban_benar, $pembahasan, $bobot);
    
    if($stmt->execute()){
        $_SESSION['pesan'] = "Soal berhasil ditambahkan!";
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan soal!";
    }
    
    $stmt_paket->close();
    $stmt->close();
    $db->close();
    
    header("Location: ../pages/kelola_soal.php?paket=$paket_soal");
    exit;
}

header("Location: ../pages/kelola_soal.php");
exit;
?>