<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

$stmt_soal = $db->prepare("SELECT COUNT(*) as total FROM soal");
$stmt_soal->execute();
$result_soal = $stmt_soal->get_result();
$total_soal = $result_soal->fetch_assoc()['total'];

$stmt_paket = $db->prepare("SELECT COUNT(*) as total FROM paket_soal");
$stmt_paket->execute();
$result_paket = $stmt_paket->get_result();
$total_paket = $result_paket->fetch_assoc()['total'];

$stmt_user = $db->prepare("SELECT COUNT(*) as total FROM user WHERE role='user'");
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$total_user = $result_user->fetch_assoc()['total'];

$stmt_list = $db->prepare("SELECT p.*, COUNT(s.id) as jumlah_soal_tersedia 
                          FROM paket_soal p 
                          LEFT JOIN soal s ON p.id_paket = s.paket_soal 
                          GROUP BY p.id_paket 
                          ORDER BY p.kelas, p.mata_pelajaran, p.judul");
$stmt_list->execute();
$result_list_paket = $stmt_list->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
            background: linear-gradient(180deg, #ffffff 0%, #f7f7ff 60%, #f2f2ff 100%);
            background-attachment: fixed;
        }
    </style>    
</head>
<body>
    <nav class="navbar navbar-light bg-transparent text-dark">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="dashboard_admin.php">Admin</a>
            <span class="text-dark">Admin: <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
            <button class="btn btn-dark ">
                            <a class="nav-link text-white" href="../../authenthication/logout.php">Logout</a>
                        </button>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center display-4 fw-bold mb-5">Dashboard Admin</h1>
        
        <div class="row mb-5 text-center">
            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Soal</h5>
                        <h2><?php echo htmlspecialchars($total_soal); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Paket Soal</h5>
                        <h2><?php echo htmlspecialchars($total_paket); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Total User</h5>
                        <h2><?php echo htmlspecialchars($total_user); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Daftar Paket Soal</h3>
            <hr>

            <div class="d-flex gap-2">
                <a href="kelola_soal.php" class="btn btn-dark">Kelola Soal</a>
                <a href="kelola_paket.php" class="btn btn-dark">Kelola Paket Soal</a>
            </div>
        </div>
        <div class="table-responsive rounded-4">
            <table class="table table-striped table-bordered bg-white">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Target Soal</th>
                        <th>Soal Tersedia</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($paket = $result_list_paket->fetch_assoc()): 
                        $status = $paket['jumlah_soal_tersedia'] >= $paket['jumlah_soal'] ? 'Lengkap' : 'Belum Lengkap';
                        $badge_class = $status == 'Lengkap' ? 'bg-success' : 'bg-warning';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($paket['judul']); ?></td>
                        <td><?php echo htmlspecialchars($paket['mata_pelajaran']); ?></td>
                        <td><?php echo htmlspecialchars($paket['kelas']); ?></td>
                        <td><?php echo htmlspecialchars($paket['jumlah_soal']); ?></td>
                        <td><?php echo htmlspecialchars($paket['jumlah_soal_tersedia']); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo $status; ?></span></td>
                        <td>
                            <a href="kelola_soal.php?paket=<?php echo htmlspecialchars($paket['id_paket']); ?>" class="btn btn-sm btn-dark">Kelola Soal</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php

$stmt_soal->close();
$stmt_paket->close();
$stmt_user->close();
$stmt_list->close();
$db->close();
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>