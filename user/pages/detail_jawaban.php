<?php
session_start();
include '../../config/connection.php';

$nilai_id = $_GET['id'];
$user_id = $_SESSION['id_user'];

$stmt_nilai = $db->prepare("SELECT n.*, p.judul, p.mata_pelajaran 
                            FROM nilai n
                            JOIN paket_soal p ON n.soal_paket = p.id_paket
                            WHERE n.id_nilai = ? AND n.user = ?");
$stmt_nilai->bind_param("ii", $nilai_id, $user_id);
$stmt_nilai->execute();
$result_nilai = $stmt_nilai->get_result();
$data_nilai = $result_nilai->fetch_assoc();

if(!$data_nilai) {
    header("Location: dashboard.php");
    exit();
}

$stmt_jawaban = $db->prepare("SELECT j.*, s.soal, s.pilihan_a, s.pilihan_b, s.pilihan_c, s.pilihan_d, s.jawaban_benar, s.pembahasan
                              FROM jawaban j
                              JOIN soal s ON j.soal_id = s.id
                              WHERE j.nilai_id = ?
                              ORDER BY s.id");
$stmt_jawaban->bind_param("i", $nilai_id);
$stmt_jawaban->execute();
$result_jawaban = $stmt_jawaban->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jawaban</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            scrollbar-gutter: stable;
        }
        .card-hover { transition: .2s; }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 1rem 1.5rem rgba(0,0,0,.4);
        }
        .text-color{
            color: #FF991C;
        }
        .bg-color{
            background-color: #FFB255;
        }
        body {
            background: linear-gradient(180deg, #ffffff 0%, #f7f7ff 60%, #f2f2ff 100%);
            background-attachment: fixed;
        }
    </style>
</head>
<body >
    <nav class="navbar navbar-expand-lg">
    <div class="container d-flex  ">
        <a class="navbar-brand fs-3 fw-bold" href="#">MikirKids</a>
        <form class="d-flex">
        <a href="dashboard.php" class="btn btn-outline bg-color me-2">Kembali</a>
        </form>
    </div>
    </nav>

    <div class="container mt-4">
        <div class="card mb-4 rounded-4 border-0">
            <div class="card-body">
                <h3><?php echo htmlspecialchars($data_nilai['judul']); ?></h3>
                <p class="mb-0">Mata Pelajaran: <strong><?php echo htmlspecialchars($data_nilai['mata_pelajaran']); ?></strong></p>
                <hr>
                <div class="row text-center">
                    <div class="col-4">
                        <h5>Nilai</h5>
                        <h2 class="text-color fw-bold"><?php echo number_format($data_nilai['nilai'], 0); ?></h2>
                    </div>
                    <div class="col-4">
                        <h5>Benar</h5>
                        <h2 class="text-success"><?php echo htmlspecialchars($data_nilai['jumlah_benar']); ?></h2>
                    </div>
                    <div class="col-4">
                        <h5>Salah</h5>
                        <h2 class="text-danger"><?php echo htmlspecialchars($data_nilai['jumlah_salah']); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3">Detail Jawaban per Soal</h4>
        
        <?php $no = 1; while($jawaban = $result_jawaban->fetch_assoc()): ?>
        
        <div class="card mb-3 card-hover w-75 mx-auto rounded-4 border-0 
        <?php echo $jawaban['is_correct'] ? 'border-success' : 'border-danger'; ?>">
            <div class="card-header <?php echo $jawaban['is_correct'] ? 'bg-success' : 'bg-danger'; ?> text-white">
                <strong>Soal <?php echo $no++; ?></strong>
                <?php if($jawaban['is_correct']): ?>
                    <span class="float-end">BENAR</span>
                <?php else: ?>
                    <span class="float-end">SALAH</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <p class="fw-bold"><?php echo htmlspecialchars($jawaban['soal']); ?></p>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>A.</strong> <?php echo htmlspecialchars($jawaban['pilihan_a']); ?></p>
                        <p class="mb-1"><strong>B.</strong> <?php echo htmlspecialchars($jawaban['pilihan_b']); ?></p>
                        <p class="mb-1"><strong>C.</strong> <?php echo htmlspecialchars($jawaban['pilihan_c']); ?></p>
                        <p class="mb-1"><strong>D.</strong> <?php echo htmlspecialchars($jawaban['pilihan_d']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <div class="alert bg-color">
                            <strong>Jawaban Kamu:</strong> 
                            <span class="fs-5"><?php echo strtoupper(htmlspecialchars($jawaban['jawaban_siswa'])); ?></span>
                        </div>
                        <div class="alert alert-warning">
                            <strong>Jawaban Benar:</strong> 
                            <span class="fs-5"><?php echo strtoupper(htmlspecialchars($jawaban['jawaban_benar'])); ?></span>
                        </div>
                        <div class="alert alert-warning">
                            <strong>Poin:</strong> <?php echo htmlspecialchars($jawaban['poin']); ?>
                        </div>
                        <?php if (!empty($jawaban['pembahasan'])): ?>
                            <div class="alert alert-secondary mt-3">
                                <strong>Pembahasan:</strong><br>
                                <?php echo nl2br(htmlspecialchars($jawaban['pembahasan'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endwhile; ?>
        
        <div class="text-center my-4">
            <a href="dashboard.php" class="btn btn-primary btn-lg">Kembali ke Dashboard</a>
        </div>
    </div>
<?php

$stmt_nilai->close();
$stmt_jawaban->close();
$db->close();
?>
</body>
</html>