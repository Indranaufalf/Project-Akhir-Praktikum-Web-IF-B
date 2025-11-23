<?php
    session_start();
    if(isset($_SESSION["username"])){
        header("Location: ../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card-hover {
    transition: .2s ease;
    background-color: #FFB255;
    }
    .card-hover:hover {
        background-color: #FFB255;
        transform: translateY(-1px);
        box-shadow: 0 1rem 1.5rem rgba(0,0,0,.4);
    }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-white position-relative">

    <div class="position-fixed top-0 start-0 end-0 bottom-0" 
        style="
        background-image: radial-gradient(circle at 50% 100%, rgba(253, 224, 71, 0.4) 0%, transparent 60%),
          radial-gradient(circle at 50% 100%, rgba(251, 191, 36, 0.4) 0%, transparent 70%),
          radial-gradient(circle at 50% 100%, rgba(244, 114, 182, 0.5) 0%, transparent 80%);
        opacity: 0.6;
        mix-blend-mode: multiply;
        z-index: -1;
        ">
    </div>

    <div class="card shadow border-0" style="width: 22rem;">
        <div class="card-body">
        <h3 class="h3 text-center mt-3 mb-4 fw-bold">Mikirkids</h3>

    <form action="proses/login_proses.php" method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control form-control-sm" name="username" placeholder="Masukkan Username" required>
            <?php if(isset($_GET["error"]) && $_GET["error"] == "username"): ?>
                <small class="text-danger">Username tidak ditemukan!</small>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control form-control-sm" name="password" placeholder="Masukkan Password" required>
            <?php if(isset($_GET["error"]) && $_GET["error"] == "password"): ?>
                <small class="text-danger">Password salah!</small>
            <?php endif; ?>
        </div>

        <div class="d-grid">
            <button class="btn card-hover fw-semibold" type="submit">Login</button>
        </div>
    </form>

        <p class="text-center mt-3 mb-0" style="font-size: 0.9rem;">
            Belum punya akun? 
            <a href="register.php" class="text-decoration-none text-primary fw-medium">Daftar Sekarang</a>
        </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
