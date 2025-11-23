<?php
session_start();
include '../../config/connection.php';

$nilai_id = $_GET['id'];
if (!isset($_SESSION['id_user'])) {
    echo "Session hilang. Silakan login ulang.";
    exit();
}

$stmt = $db->prepare("SELECT n.*, p.judul 
                     FROM nilai n
                     JOIN paket_soal p ON n.soal_paket = p.id_paket
                     WHERE n.id_nilai = ?");
$stmt->bind_param("i", $nilai_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card text-center">
            <div class="card-body">
                <h2>Hasil Ujian</h2>
                <h4><?php echo htmlspecialchars($data['judul']); ?></h4>
                <hr>
                
                <h1 class="text-primary"><?php echo number_format($data['nilai'], 0); ?></h1>
                <p class="fs-4">
                    <?php 
                    if($data['nilai'] >= 80) echo "W SIGMA ABIS!";
                    else if($data['nilai'] >= 70) echo "Bagus!";
                    else if($data['nilai'] >= 60) echo "NICE TRY!";
                    else echo "BIG L!";
                    ?>
                </p>
                <hr>
                <div class="row mt-4">
                    <div class="col-6">
                        <h5 class="text-success">Benar</h5>
                        <h2><?php echo htmlspecialchars($data['jumlah_benar']); ?></h2>
                    </div>
                    <div class="col-6">
                        <h5 class="text-danger">Salah</h5>
                        <h2><?php echo htmlspecialchars($data['jumlah_salah']); ?></h2>
                    </div>
                </div>
                <hr>
                <a href="dashboard.php" class="btn btn-primary btn-lg">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
<?php
$stmt->close();
$db->close();
?>
</body>
</html>