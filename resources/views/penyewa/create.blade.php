<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking GOR</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm px-4" style="background: #0b1220;">
    <div class="container-fluid">

        <span class="navbar-brand fw-bold">
            <i class="fa-solid fa-calendar-check me-2"></i>
            Booking GOR
        </span>

        <a href="/penyewa/dashboard" class="btn btn-outline-light btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
        </a>

    </div>
</nav>

<div class="container mt-4">

    <div class="card-custom p-4">
        <h4 class="mb-3">
            <i class="fa-solid fa-pen-to-square"></i> Form Booking
        </h4>

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

        <form action="{{ route('penyewa.booking.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>

            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                       value="{{ $tanggal ?? '' }}" required readonly>
            </div>

            <div class="mb-3">
                <label>Jam Mulai</label>
                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Durasi (Jam)</label>
                <select id="durasi" name="durasi" class="form-control" required>
                    <option value="">-- Pilih Durasi --</option>
                    <option value="1">1 Jam</option>
                    <option value="2">2 Jam</option>
                    <option value="3">3 Jam</option>
                    <option value="4">4 Jam</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Jam Selesai (Otomatis)</label>
                <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label>Total Harga</label>
                <input type="text" id="total_harga" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label>Keperluan</label>
                <textarea name="keperluan" class="form-control" required></textarea>
            </div>

            <button class="btn btn-primary w-100 mb-2">
                <i class="fa-solid fa-paper-plane"></i> Booking Sekarang
            </button>

        </form>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const jamMulai = document.getElementById('jam_mulai');
        const tanggal = document.querySelector('input[name="tanggal"]');
        const durasi = document.getElementById('durasi');
        const jamSelesai = document.getElementById('jam_selesai');
        const totalHarga = document.getElementById('total_harga');

        function setMinJam() {
            let selectedDate = tanggal.value;
            let today = new Date().toISOString().split("T")[0];

            if (selectedDate === today) {
                let now = new Date();
                let jam = now.getHours().toString().padStart(2, '0');
                let menit = now.getMinutes().toString().padStart(2, '0');

                jamMulai.min = `${jam}:${menit}`;
            } else {
                jamMulai.min = "00:00";
            }
        }

        setMinJam();

        function hitung() {
            let mulai = jamMulai.value;
            let jam = parseInt(durasi.value);

            if (!mulai || !jam) return;

            let [h, m] = mulai.split(':');

            let mulaiDate = new Date();
            mulaiDate.setHours(h);
            mulaiDate.setMinutes(m);

            mulaiDate.setHours(mulaiDate.getHours() + jam);

            let selesaiJam = mulaiDate.getHours().toString().padStart(2, '0');
            let selesaiMenit = mulaiDate.getMinutes().toString().padStart(2, '0');

            jamSelesai.value = `${selesaiJam}:${selesaiMenit}`;

            let harga = jam * 50000;
            totalHarga.value = "Rp " + harga.toLocaleString('id-ID');
        }

        jamMulai.addEventListener('change', hitung);
        durasi.addEventListener('change', hitung);

    });
</script>

</body>
</html>
