<?php
session_start();
include '../../config/connection.php';

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../../authenthication/login.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM paket_soal ORDER BY kelas, mata_pelajaran");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket</title>
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


    <div class="container mt-4 mb-5 ">
        <div class="card bg-transparent shadow border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Kelola Paket Soal</h4>
                <button class="btn btn-secondary btn-sm rounded-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    Tambah Paket
                </button>
            </div>
            <div class="card-body">
                
                <?php if(isset($_SESSION['pesan'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive text-center rounded-4">
                    <table class="table table-borderless">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jumlah Soal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($data = $result->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($data['judul']); ?></td>
                                <td><?php echo htmlspecialchars($data['mata_pelajaran']); ?></td>
                                <td><?php echo htmlspecialchars($data['kelas']); ?></td>
                                <td><?php echo htmlspecialchars($data['jumlah_soal']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-secondary rounded-4" 
                                            onclick="editPaket(<?php echo htmlspecialchars(json_encode($data)); ?>)">
                                        Edit
                                    </button>
                                    <a href="../proses/proses_hapus_paket.php?id=<?php echo htmlspecialchars($data['id_paket']); ?>" 
                                       class="btn btn-sm btn-secondary rounded-4"
                                       onclick="return confirm('Yakin hapus paket ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../proses/proses_tambah_paket.php">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Tambah Paket Soal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Paket *</label>
                            <input type="text" class="form-control" name="judul" 
                                   placeholder="Contoh: UTS Matematika Kelas 1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran *</label>
                            <select class="form-select" name="mata_pelajaran" required>
                                <option value="">-- Pilih --</option>
                                <option value="Matematika">Matematika</option>
                                <option value="IPA">IPA</option>
                                <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelas *</label>
                            <select class="form-select" name="kelas" required>
                                <option value="">-- Pilih --</option>
                                <?php for($i=1; $i<=6; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Soal *</label>
                            <input type="number" class="form-control" name="jumlah_soal" 
                                   value="20" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-secondary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../proses/proses_edit_paket.php">
                    <input type="hidden" name="id_paket" id="edit_id">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Edit Paket Soal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Paket *</label>
                            <input type="text" class="form-control" name="judul" id="edit_judul" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran *</label>
                            <select class="form-select" name="mata_pelajaran" id="edit_mapel" required>
                                <option value="Matematika">Matematika</option>
                                <option value="IPA">IPA</option>
                                <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kelas *</label>
                            <select class="form-select" name="kelas" id="edit_kelas" required>
                                <?php for($i=1; $i<=6; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Soal *</label>
                            <input type="number" class="form-control" name="jumlah_soal" id="edit_jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
$stmt->close();
$db->close();
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editPaket(data) {
        document.getElementById('edit_id').value = data.id_paket;
        document.getElementById('edit_judul').value = data.judul;
        document.getElementById('edit_mapel').value = data.mata_pelajaran;
        document.getElementById('edit_kelas').value = data.kelas;
        document.getElementById('edit_jumlah').value = data.jumlah_soal;
        
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }
    </script>
</body>
</html>