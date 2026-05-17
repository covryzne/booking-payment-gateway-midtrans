<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penyewa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
        }

        .card-custom {
            border-radius: 15px;
            background: white;
            color: black;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        #calendar {
            background: white;
            padding: 15px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4" style="background: #0b1220;">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            <i class="fa-solid fa-user-gear me-2"></i>
            Dashboard Penyewa
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm px-3">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </button>
        </form>

    </div>
</nav>

<div class="container mt-4">

    <h3 class="mb-4">
        Selamat Datang, {{ auth()->user()->name }} 👋
    </h3>

    <div class="row">

        <!-- KALENDER -->
        <div class="col-md-8">
            <div class="card-custom p-3">
                <h5 class="mb-3">
                    <i class="fa-solid fa-calendar-days"></i> Pilih Tanggal Booking
                </h5>

                <div id="calendar"></div>
            </div>
        </div>

        <!-- INFO + DETAIL -->
        <div class="col-md-4">

            <div class="card-custom p-3">
                <h5>
                    <i class="fa-solid fa-circle-info"></i> Informasi
                </h5>

                <p class="mt-3">
                    Klik tanggal untuk booking.
                </p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="card-custom p-3 mt-3">
                    <h5>
                        <i class="fa-solid fa-list"></i> Detail Booking
                    </h5>

                    <div id="booking-detail" class="mt-3">
                        <p class="text-muted">Klik tanggal untuk melihat detail sewa jam.</p>
                    </div>
                </div>

                <div class="card-custom p-3 mt-3">
                <h5>
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Booking
                </h5>

                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                <p class="text-muted mt-2">
                    Lihat semua riwayat booking Anda.
                </p>

                <a href="{{ route('penyewa.riwayat') }}" class="btn btn-primary w-100">
                    <i class="fa-solid fa-list me-1"></i> Lihat Riwayat
                </a>
            </div>

            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>

let bookings = @json($bookings);

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let events = bookings.map(item => {
        return {
            title: 'Booked',
            start: item.tanggal,
            allDay: true,
            display: 'background',
            backgroundColor: '#dc3545'
        }
    });

    let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: events,

    validRange: {
        start: new Date().toISOString().split("T")[0]
    },

        dateClick: function(info) {
            let tanggal = info.dateStr;

            let dataTanggal = bookings.filter(item => item.tanggal === tanggal);

            let html = `<h6 class="mb-3">Tanggal: ${tanggal}</h6>`;

            if (dataTanggal.length > 0) {
                let now = new Date();
                let today = now.toISOString().split("T")[0];
                let currentTime = now.getHours() * 60 + now.getMinutes();

                dataTanggal.forEach(b => {

                let jamMulai = b.jam_mulai.substring(0,5);
                let [h, m] = jamMulai.split(":");
                let bookingTime = parseInt(h) * 60 + parseInt(m);

            // ❌ kalau hari ini & jam sudah lewat → skip
            if (tanggal === today && bookingTime <= currentTime) {
                return;
            }
                html += `
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body p-2">
                            <strong>
                                ${b.jam_mulai.substring(0,5)} - ${b.jam_selesai.substring(0,5)}
                            </strong>
                        </div>
                    </div>
                `;
            });
            } else {
                html += `<p class="text-muted">Belum ada booking</p>`;
            }

            html += `
                <a href="/booking?tanggal=${tanggal}" class="btn btn-primary w-100 mt-2">
                    Booking Tanggal Ini
                </a>
            `;

            document.getElementById('booking-detail').innerHTML = html;
        }
    });

    calendar.render();
});

</script>

<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        location.replace("/login");
    };
</script>
</body>
</html>
