<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../proses/login.php");
    exit;
}

$filter_paket = isset($_GET['paket']) ? $_GET['paket'] : '';

$stmt_paket = $db->prepare("SELECT * FROM paket_soal ORDER BY kelas, mata_pelajaran");
$stmt_paket->execute();
$query_paket = $stmt_paket->get_result();

if($filter_paket) {
    $stmt_soal = $db->prepare("SELECT s.*, p.judul as nama_paket, p.mata_pelajaran, p.kelas 
                              FROM soal s 
                              JOIN paket_soal p ON s.paket_soal = p.id_paket 
                              WHERE s.paket_soal = ?
                              ORDER BY s.id");
    $stmt_soal->bind_param("i", $filter_paket);
} else {
    $stmt_soal = $db->prepare("SELECT s.*, p.judul as nama_paket, p.mata_pelajaran, p.kelas 
                              FROM soal s 
                              JOIN paket_soal p ON s.paket_soal = p.id_paket 
                              ORDER BY s.id");
}
$stmt_soal->execute();
$query_soal = $stmt_soal->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    body {
            background: linear-gradient(180deg, #ffffff 0%, #f7f7ff 60%, #f2f2ff 100%);
            background-attachment: fixed;
    }
    </style> 
</head>
<body>
    <nav class="navbar navbar-light bg-transparent text-dark position-relative mt-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="dashboard_admin.php" class="text-dark fs-4">
                <i class="bi bi-arrow-left-circle-fill"></i>
            </a>

            <span class="navbar-brand fw-bold fs-4 position-absolute start-50 translate-middle-x">
                Admin
            </span>

            <span class="text-dark">
                Admin: <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </span>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Kelola Soal</h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($_SESSION['pesan'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-8">
                        <form method="GET" class="row g-2">
                            <div class="col-md-8">
                                <select name="paket" class="form-select">
                                    <option value="">-- Semua Paket Soal --</option>
                                    <?php 
                                    // Reset pointer untuk query_paket
                                    $query_paket->data_seek(0);
                                    while($p = $query_paket->fetch_assoc()): 
                                    ?>
                                    <option value="<?php echo htmlspecialchars($p['id_paket']); ?>" <?php echo $filter_paket == $p['id_paket'] ? 'selected' : ''; ?>>
                                        Kelas <?php echo htmlspecialchars($p['kelas']); ?> - <?php echo htmlspecialchars($p['mata_pelajaran']); ?> - <?php echo htmlspecialchars($p['judul']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-warning">Filter</button>
                                <a href="kelola_soal.php" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            + Tambah Soal
                        </button>
                    </div>
                </div>

    <section class="tabel soal">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Paket</th>
                                <th width="35%">Soal</th>
                                <th width="10%">Jawaban</th>
                                <th width="8%">Bobot</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($query_soal->num_rows > 0):
                                $no = 1;
                                while($s = $query_soal->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <small>
                                        <b>Kelas <?php echo htmlspecialchars($s['kelas']); ?></b><br>
                                        <?php echo htmlspecialchars($s['mata_pelajaran']); ?><br>
                                        <?php echo htmlspecialchars($s['nama_paket']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars(substr($s['soal'], 0, 80)); ?>...<br>
                                    <small class="text-muted">
                                        A: <?php echo htmlspecialchars(substr($s['pilihan_a'], 0, 20)); ?>...<br>
                                        B: <?php echo htmlspecialchars(substr($s['pilihan_b'], 0, 20)); ?>...
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success"><?php echo strtoupper(htmlspecialchars($s['jawaban_benar'])); ?></span>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($s['bobot']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" 
                                            onclick='editSoal(<?php echo json_encode($s); ?>)'>
                                        Edit
                                    </button>
                                    <a href="../proses/proses_hapus_soal.php?id=<?php echo htmlspecialchars($s['id']); ?>&paket=<?php echo htmlspecialchars($s['paket_soal']); ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin hapus soal ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada soal</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
        </div>
    </section>

    <section class="tambah soal">
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="../proses/proses_tambah_soal.php">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Tambah Soal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Paket Soal *</label>
                            <select name="paket_soal" class="form-select" required>
                                <option value="">-- Pilih Paket --</option>
                                <?php 
                                $query_paket->data_seek(0);
                                while($p = $query_paket->fetch_assoc()): 
                                ?>
                                <option value="<?php echo htmlspecialchars($p['id_paket']); ?>" <?php echo $filter_paket == $p['id_paket'] ? 'selected' : ''; ?>>
                                    Kelas <?php echo htmlspecialchars($p['kelas']); ?> - <?php echo htmlspecialchars($p['mata_pelajaran']); ?> - <?php echo htmlspecialchars($p['judul']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Soal *</label>
                            <textarea name="soal" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan A *</label>
                                <input type="text" name="pilihan_a" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan B *</label>
                                <input type="text" name="pilihan_b" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan C *</label>
                                <input type="text" name="pilihan_c" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan D *</label>
                                <input type="text" name="pilihan_d" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jawaban Benar *</label>
                                <select name="jawaban_benar" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Bobot Nilai *</label>
                                <input type="number" name="bobot" class="form-control" value="5" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Pembahasan *</label>
                            <textarea name="pembahasan" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>

    <section class="edit soal">
    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="../proses/proses_edit_soal.php">
                    <input type="hidden" name="id_soal" id="edit_id">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Edit Soal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Paket Soal *</label>
                            <select name="paket_soal" id="edit_paket" class="form-select" required>
                                <?php 
                                $query_paket->data_seek(0);
                                while($p = $query_paket->fetch_assoc()): 
                                ?>
                                <option value="<?php echo htmlspecialchars($p['id_paket']); ?>">
                                    Kelas <?php echo htmlspecialchars($p['kelas']); ?> - <?php echo htmlspecialchars($p['mata_pelajaran']); ?> - <?php echo htmlspecialchars($p['judul']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Soal *</label>
                            <textarea name="soal" id="edit_soal" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan A *</label>
                                <input type="text" name="pilihan_a" id="edit_a" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan B *</label>
                                <input type="text" name="pilihan_b" id="edit_b" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan C *</label>
                                <input type="text" name="pilihan_c" id="edit_c" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Pilihan D *</label>
                                <input type="text" name="pilihan_d" id="edit_d" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jawaban Benar *</label>
                                <select name="jawaban_benar" id="edit_jawaban" class="form-select" required>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Bobot Nilai *</label>
                                <input type="number" name="bobot" id="edit_bobot" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Pembahasan *</label>
                            <textarea name="pembahasan" id="edit_pembahasan" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>

<?php
$stmt_paket->close();
$stmt_soal->close();
$db->close();
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editSoal(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_paket').value = data.paket_soal;
        document.getElementById('edit_soal').value = data.soal;
        document.getElementById('edit_a').value = data.pilihan_a;
        document.getElementById('edit_b').value = data.pilihan_b;
        document.getElementById('edit_c').value = data.pilihan_c;
        document.getElementById('edit_d').value = data.pilihan_d;
        document.getElementById('edit_jawaban').value = data.jawaban_benar;
        document.getElementById('edit_bobot').value = data.bobot;
        document.getElementById('edit_pembahasan').value = data.pembahasan;
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
    </script>
</body>
</html>