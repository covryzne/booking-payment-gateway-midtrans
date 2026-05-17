<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
        }

        .card-dashboard {
            border-radius: 15px;
            background: white;
            color: black;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
    </style>
</head>

<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4" style="background: #0b1220;">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            <i class="fa-solid fa-user-shield"></i> Dashboard Admin
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm px-3">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </button>
        </form>

    </div>
</nav>


<!-- 🔵 CONTENT -->
<div class="container mt-5">

    <h2 class="mb-4">Selamat Datang, {{ auth()->user()->name }} 👋</h2>

    <div class="row">

        <!-- Card Booking -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.booking') }}" class="text-decoration-none text-dark">
                <div class="card card-dashboard p-4 text-center" style="cursor: pointer;">
                    <i class="fa-solid fa-calendar-check fa-2x text-primary mb-3"></i>
                    <h5>Data Booking</h5>
                    <p>Lihat semua data penyewaan</p>
                </div>
            </a>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('admin.laporan') }}" class="text-decoration-none text-dark">
                <div class="card card-dashboard p-4 text-center" style="cursor: pointer;">
                    <i class="fa-solid fa-chart-line fa-2x text-warning mb-3"></i>
                    <h5>Laporan</h5>
                    <p>Monitoring dan laporan penyewaan</p>
                </div>
            </a>
        </div>

    </div>

</div>
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        location.replace("/login");
    };
</script>

</body>
</html>
