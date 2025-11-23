<?php
session_start();
include '../../config/connection.php';

$paket_id = $_GET['id'];

$stmt_paket = $db->prepare("SELECT * FROM paket_soal WHERE id_paket = ?");
$stmt_paket->bind_param("i", $paket_id);
$stmt_paket->execute();
$result_paket = $stmt_paket->get_result();
$paket = $result_paket->fetch_assoc();

$stmt_soal = $db->prepare("SELECT * FROM soal WHERE paket_soal = ?");
$stmt_soal->bind_param("i", $paket_id);
$stmt_soal->execute();
$result_soal = $stmt_soal->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            scrollbar-gutter: stable;
        }
        .card-hover { 
            transition: .2s; 
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 1rem 1.5rem rgba(0,0,0,.4);
        }
        .text-color{
            color: #FF991C;
        }
        .btn-color{
            background-color: #FFB255;
        }
        body {
            background: linear-gradient(180deg, #ffffff 0%, #f7f7ff 60%, #f2f2ff 100%);
            background-attachment: fixed;
        }
    </style>
</head>
<body >
    <div class="container mt-5">
        <h2 class="text-center mb-5"><?php echo htmlspecialchars($paket['judul']); ?></h2>
        
        <form method="POST" action="../proses/proses_ujian.php">
            <input type="hidden" name="paket_id" value="<?php echo htmlspecialchars($paket_id); ?>">
            <?php $no = 1; while($soal = $result_soal->fetch_assoc()): ?>
            <div class="card mb-3 bg-light bg-opacity-50 rounded-4 w-75 mx-auto fs-5">
                <div class="card-body ms-5">
                    <h5>Soal <?php echo $no++; ?></h5>
                    <p><?php echo htmlspecialchars($soal['soal']); ?></p>
                    
                    <div class="form-check">
                        <input type="radio" name="jawaban[<?php echo htmlspecialchars($soal['id']); ?>]" value="A" id="<?php echo htmlspecialchars($soal['id']); ?>a">
                        <label for="<?php echo htmlspecialchars($soal['id']); ?>a">A. <?php echo htmlspecialchars($soal['pilihan_a']); ?></label>
                    </div>
                    
                    <div class="form-check">
                        <input type="radio" name="jawaban[<?php echo htmlspecialchars($soal['id']); ?>]" value="B" id="<?php echo htmlspecialchars($soal['id']); ?>b">
                        <label for="<?php echo htmlspecialchars($soal['id']); ?>b">B. <?php echo htmlspecialchars($soal['pilihan_b']); ?></label>
                    </div>
                    
                    <div class="form-check">
                        <input type="radio" name="jawaban[<?php echo htmlspecialchars($soal['id']); ?>]" value="C" id="<?php echo htmlspecialchars($soal['id']); ?>c">
                        <label for="<?php echo htmlspecialchars($soal['id']); ?>c">C. <?php echo htmlspecialchars($soal['pilihan_c']); ?></label>
                    </div>
                    
                    <div class="form-check">
                        <input type="radio" name="jawaban[<?php echo htmlspecialchars($soal['id']); ?>]" value="D" id="<?php echo htmlspecialchars($soal['id']); ?>d">
                        <label for="<?php echo htmlspecialchars($soal['id']); ?>d">D. <?php echo htmlspecialchars($soal['pilihan_d']); ?></label>
                    </div>
                </div>
            </div>
            
            <?php endwhile; ?>
            
            <div class="text-center mb-5">
                <button type="submit" class="btn btn-color card-hover btn-lg">Kumpulkan</button>
            </div>
        </form>
    </div>
<?php
$stmt_paket->close();
$stmt_soal->close();
$db->close();
?>
</body>
</html>