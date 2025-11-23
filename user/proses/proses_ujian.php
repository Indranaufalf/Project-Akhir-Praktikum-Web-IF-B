<?php
session_start();
include '../../config/connection.php';

$user_id = $_SESSION['id_user'];
$paket_id = $_POST['paket_id'];
$jawaban = $_POST['jawaban'];

$stmt = $db->prepare("SELECT * FROM soal WHERE paket_soal = ?");
$stmt->bind_param("i", $paket_id);
$stmt->execute();
$result = $stmt->get_result();

$benar = 0;
$salah = 0;

$soal_array = [];
while($soal = $result->fetch_assoc()) {
    $soal_array[] = $soal;
    
    if(isset($jawaban[$soal['id']])) {
        $jawaban_siswa = strtolower($jawaban[$soal['id']]);
        $jawaban_benar = strtolower($soal['jawaban_benar']);
        
        if($jawaban_siswa == $jawaban_benar) {
            $benar++;
        } else {
            $salah++;
        }
    } else {
        $salah++; 
    }
}

$total = $benar + $salah;
$nilai = ($benar / $total) * 100;

$stmt_simpan = $db->prepare("INSERT INTO nilai (nilai, user, soal_paket, jumlah_benar, jumlah_salah) 
                             VALUES (?, ?, ?, ?, ?)");
$stmt_simpan->bind_param("diiii", $nilai, $user_id, $paket_id, $benar, $salah);
$stmt_simpan->execute();
$nilai_id = $stmt_simpan->insert_id;

$stmt_jawaban = $db->prepare("INSERT INTO jawaban (nilai_id, soal_id, jawaban_siswa, is_correct, poin) 
                              VALUES (?, ?, ?, ?, ?)");

foreach($soal_array as $soal) {
    $soal_id = $soal['id'];
    $jawaban_siswa = isset($jawaban[$soal_id]) ? strtolower($jawaban[$soal_id]) : null;
    $jawaban_benar = strtolower($soal['jawaban_benar']);
    
    $is_correct = 0;
    $poin = 0;
    if($jawaban_siswa == $jawaban_benar) {
        $is_correct = 1;
        $poin = $soal['bobot'];
    }
    
    $stmt_jawaban->bind_param("iisii", $nilai_id, $soal_id, $jawaban_siswa, $is_correct, $poin);
    $stmt_jawaban->execute();
}

$stmt->close();
$stmt_simpan->close();
$stmt_jawaban->close();
$db->close();

header("Location: ../pages/hasil.php?id=$nilai_id");
exit();
?>