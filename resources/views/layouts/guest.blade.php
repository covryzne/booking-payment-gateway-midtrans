<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GOR Rajawali') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #000000);
        }

        /* Kiri transparan (ikut background) */
        .left-side {
            background: transparent;
            color: white;
        }

        /* Kanan transparan juga */
        .right-side {
            background: transparent;
            color: white;
        }

        /* Card tetap putih biar kontras */
        .card-custom {
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .schedule-box {
            background: white;
            color: black;
            border-radius: 15px;
        }
        .eye-icon {
    position: absolute;
    right: 15px;
    top: 38px;
    cursor: pointer;
    color: #6c757d;
    transition: 0.2s;
}

.eye-icon:hover {
    color: #0d47a1;
}
    </style>
</head>

<body>

<div class="container-fluid h-100">
    <div class="row h-100">

        <!-- 🔵 LEFT SIDE -->
        <div class="col-md-6 d-flex align-items-center justify-content-center left-side">

            <div style="width: 100%; max-width: 400px;">

                <div class="card card-custom p-4">
                    {{ $slot }}
                </div>

            </div>

        </div>

        <!-- 🟢 RIGHT SIDE -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center text-center right-side p-5">

            <h1 class="fw-bold mb-3">
                <i class="fa-solid fa-basketball"></i> GOR RAJAWALI
            </h1>

            <p class="mb-4 text-light">
                Sistem penyewaan lapangan olahraga berbasis web
                untuk booking cepat, mudah, dan efisien.
            </p>

            <!-- Jadwal -->
            <div class="schedule-box p-4 mb-4 shadow">
                <h5 class="fw-bold">
                    <i class="fa-solid fa-calendar-days"></i> Latihan Rutin
                </h5>
                <p class="mb-1">Selasa & Jumat</p>
                <p class="mb-0">19.00 - 23.00</p>
            </div>

            <!-- Motivasi -->
            <p class="fst-italic text-light">
                <i class="fa-solid fa-fire"></i> Stay fit, stay strong
            </p>

        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(fieldId, el) {
    let input = document.getElementById(fieldId);
    let icon = el.querySelector("i"); // ambil icon di dalam span

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>
