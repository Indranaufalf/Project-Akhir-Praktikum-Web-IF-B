<?php 
session_start();
include '../../config/connection.php';

$user_id = $_SESSION["id_user"];
$kelas   = $_SESSION["kelas"];

$mapel = isset($_GET["mapel"]) ? $_GET["mapel"] : null;

$stmt_mapel = $db->prepare("SELECT DISTINCT mata_pelajaran FROM paket_soal WHERE kelas = ? ORDER BY mata_pelajaran");
$stmt_mapel->bind_param("s", $kelas);
$stmt_mapel->execute();
$result_mapel = $stmt_mapel->get_result();

if ($mapel) {
    $stmt = $db->prepare("SELECT * FROM paket_soal WHERE kelas = ? AND mata_pelajaran = ? ORDER BY judul");
    $stmt->bind_param("ss", $kelas, $mapel);
    $stmt->execute();
    $result = $stmt->get_result();
}

$stmt_history = $db->prepare("SELECT n.*, p.judul, p.mata_pelajaran FROM nilai n 
                              JOIN paket_soal p ON n.soal_paket = p.id_paket 
                              WHERE n.user = ? AND p.mata_pelajaran = ?
                              ORDER BY n.id_nilai DESC");
$stmt_history->bind_param("is", $user_id, $mapel);
$stmt_history->execute();
$result_history = $stmt_history->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container d-flex">
            
            <a class="navbar-brand fs-3 fw-bold" href="#">MikirKids</a>
            <form class="d-flex">
            <a href="../../authenthication/logout.php" class="btn btn-outline btn-color me-2">Logout</a>
            </form>
        </div>
    </nav>

    <div class="container mt-5 justify-content-center">
        <h1 class="text-center display-4 fw-bold mb-4">Dashboard Ujian</h1>
        <h5 class="text-center mb-4">Kelas <?php echo htmlspecialchars($kelas); ?></h5>
        <h4 class="text-center mb-5 fs-3"> Halo, <span class="text-color"> <?php echo htmlspecialchars($_SESSION["username"]); ?>  </span></h4>
        <?php if (!$mapel): ?>
            <div class="row">
                <?php while($m = $result_mapel->fetch_assoc()): ?>
                    <div class="col-md-4 my-5">
                        <div class="card card-hover rounded-4">
                            <div class="card-body text-center">
                                <h4><?php echo htmlspecialchars($m['mata_pelajaran']); ?></h4>
                                <a href="dashboard.php?mapel=<?php echo urlencode($m['mata_pelajaran']); ?>" 
                                class="btn btn-color mt-3 w-100">
                                    Lihat Paket Soal
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        </body>
        </html>
        <?php 
        $stmt_mapel->close();
        exit; 
        endif; ?>

        <h3 class="mb-4">Paket Soal: <strong class="text-color"><?php echo htmlspecialchars($mapel); ?></strong></h3>
        <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($paket = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-3">
                    <div class="card card-hover rounded-4">
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($paket['judul']); ?></h5>
                            <p>Jumlah Soal: <?php echo htmlspecialchars($paket['jumlah_soal']); ?></p>
                            <a href="ujian.php?id=<?php echo htmlspecialchars($paket['id_paket']); ?>" 
                            class="btn btn-color">
                                Mulai Ujian
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-danger">Belum ada paket soal untuk mapel ini.</p>
        <?php endif; ?>
        </div>
        <hr class="my-5">
        <h3 class="mb-3">History Ujian</h3>

        <?php if($result_history->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Ujian</th>
                        <th>Mata Pelajaran</th>
                        <th>Nilai</th>
                        <th>Benar</th>
                        <th>Salah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while($history = $result_history->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($history['judul']); ?></td>
                        <td><?php echo htmlspecialchars($history['mata_pelajaran']); ?></td>
                        <td><strong class="fs-5"><?php echo number_format($history['nilai'],0); ?></strong></td>
                        <td class="text-success"><strong><?php echo htmlspecialchars($history['jumlah_benar']); ?></strong></td>
                        <td class="text-danger"><strong><?php echo htmlspecialchars($history['jumlah_salah']); ?></strong></td>
                        <td>
                            <?php if ($history['nilai'] >= 70): ?>
                                <span class="badge bg-success">LULUS</span>
                            <?php else: ?>
                                <span class="badge bg-danger">TIDAK LULUS</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="detail_jawaban.php?id=<?php echo htmlspecialchars($history['id_nilai']); ?>" 
                            class="btn btn-sm btn-color">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-info bg-transparent shadow-sm">
                Belum ada history ujian untuk mapel ini.
            </div>
        <?php endif; ?>

        <div class="text-center mt-4 mb-5">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Mapel</a>
        </div>
</div>
<?php

if(isset($stmt)) $stmt->close();
$stmt_mapel->close();
$stmt_history->close();
$db->close();
?>
</body>
</html>