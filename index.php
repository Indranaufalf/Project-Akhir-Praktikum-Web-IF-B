<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .navbar-collapse { flex-grow: 0 !important; }
    .card-hover {
        background-color: #FFB255;
        transition: .2s;
    }
    .card-hover:hover {
        background-color: #FFB255;
        transform: translateY(-4px);
        box-shadow: 0 1rem 1.5rem rgba(0,0,0,.4);
    }
    .btn-1 {
    background-color: #ffe6caff;
    }
    .btn-2 {
        background-color: #FFB255;
    }
    </style>
</head>
<body>
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

    <nav class="navbar navbar-expand-lg">
    <div class="container d-flex justify-content-evenly align-items-center">
        <a class="navbar-brand fs-3 fw-bold" href="#">MikirKids</a>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#aboutus">About Us</a>
            </li>
        </ul>
        </div>
        <form class="d-flex">
        <a href="authenthication/register.php" class="btn  btn-1 me-2">Sign Up</a>
        <a href="authenthication/login.php" class="btn  btn-2 me-2">Login</a>
        </form>

    </div>
    </nav>

    <div class="d-flex justify-content-center align-items-center text-center vh-100" style="margin-top:-56px;">
    <div>
        <h1 class="fw-bold">MikirKids</h1>
        <div style="width:200px; height:3px; background:orange; margin:10px auto;"></div>
        <h2 class="display-5 fs-4">Present</h2>
    </div>
    </div>


    <div id="aboutus" class="container py-5" style="max-width: 800px;">
        <header class="text-center mb-5">
            <h3 class="text-dark mb-2">About Us</h3>
            <h3 class="text-dark fw-bold">
                Mikirkids: Solusi Latihan Soal Terbaik untuk Siswa SD
            </h3>
        </header>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <p class="fs-6 mb-4 text-center">
                Mikirkids adalah platform latihan soal daring yang dirancang khusus untuk membantu siswa Sekolah Dasar (SD) meningkatkan pemahaman dan kesiapan mereka menghadapi ujian. Kami berkomitmen menyediakan materi yang efektif, terstruktur, dan mudah diakses.
            </p>
            <hr class="my-5">
            <h4 class="mb-4 text-dark fw-bold text-center">Fokus Layanan Mikirkids</h4>
            <ul class="list-group list-group-flush fs-6">
                <li class="list-group-item bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-primary rounded-pill me-3 p-2 flex-shrink-0">1</span>
                        <div>
                            <div class="fw-bold mb-1">Cakupan Jenjang Kelas Lengkap</div>
                            <div class="text-muted">Tersedia untuk Siswa <strong>Kelas 1 hingga Kelas 6</strong> SD.</div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item bg-transparent border-bottom py-3">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-success rounded-pill me-3 p-2 flex-shrink-0">2</span>
                        <div>
                            <div class="fw-bold mb-1">Paket Soal Terpadu (Combo)</div>
                            <div class="text-muted">Setiap paket soal terdiri dari <strong>3 mata pelajaran inti</strong> untuk latihan yang efisien.</div>
                        </div>
                    </div>
                </li>
                <li class="list-group-item bg-transparent py-3">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-danger rounded-pill me-3 p-2 flex-shrink-0">3</span>
                        <div>
                            <div class="fw-bold mb-1">Fokus Ujian Semester</div>
                            <div class="text-muted">Materi persiapan <strong>Ujian Tengah Semester (UTS)</strong> dan <strong>Ujian Akhir Semester (UAS)</strong>.</div>
                        </div>
                    </div>
                </li>
            </ul>
            <hr class="my-5">
            <div class="text-center">
                <p class="fs-6 mb-4">
                    Tingkatkan prestasi akademik anak Anda bersama Mikirkids. Mulai latihan hari ini juga!
                </p>
                <a href="authenthication/login.php" class="btn bg-color fw-bold card-hover rounded-pill px-5 py-3">
                    Mulai Latihan Sekarang
                </a>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
